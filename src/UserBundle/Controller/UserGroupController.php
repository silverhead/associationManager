<?php

namespace UserBundle\Controller;

use AppBundle\Event\CredentialEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserGroupController extends Controller
{
    const ITEMS_PER_PAGE = 4;
    const PAGE_PARAMETER_NAME = 'pageTab3';

    /**
     * @Route("/user/group/list-part/{anchor}", name="user_group_list_part",  options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $anchor = null)
    {
        $page =  $request->get(self::PAGE_PARAMETER_NAME, 1);
        $currentRoute = 'user_manager';

        $userGroupManager = $this->get('user.manager.group');

        $results = $userGroupManager->paginatedList(
            $page,
            self::ITEMS_PER_PAGE,
            self::PAGE_PARAMETER_NAME,
            $anchor,
            $currentRoute
        );

        $pageH = $this->get('app.handler.page_historical');

        $pageH->setCallbackUrl('user_group_edit',
            $this->generateUrl($currentRoute),
            [self::PAGE_PARAMETER_NAME => $page],
            $anchor
        );

        return $this->render('/user/group/groupsList.html.twig', array(
            'menuSelect' => 'user_manager',
            'results' => $results
        ));
    }

    /**
     * @Route("/user/group/add", name="user_group_add")
     * @Route("/user/group/edit/{id}", name="user_group_edit")
     * @param Request $request
     */
    public function editAction(Request $request, $id = null)
    {
        $translator = $this->get('translator');

        if(
        (!$this->isGranted("USER_GROUP_EDIT") && !$this->isGranted("USER_GROUP_CREATE"))
        ){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));

            return $this->redirect(
                $this->generateUrl('dashboard')
            );
        }

        $manager = $this->get('user.manager.group');
        $formHandler = $this->get('user.form.handler.group');

        $entity = $manager->find($id);
        if(null === $entity){
            $entity = $manager->getNewEntity();
        }

        $pageH = $this->get('app.handler.page_historical');
        $callBackUrl = $pageH->getCallbackUrl('user_group_edit');

        $formHandler->setForm($entity);

        if($formHandler->process($request)){
            $form = $formHandler->getForm();
            $entity->setLabel($form['label']->getData());
            $entity->setActive($form['active']->getData());

            $entity->setCredentials(
                $form['credentials']->getData()
            );

            if($manager->save($entity)){
                $this->addFlash('success', $translator->trans('user.group.edit.saveSuccessText'));

                if(null !== $request->get('save_and_leave', null)){
                    if(null!== $callBackUrl){
                        return $this->redirect($callBackUrl);
                    }

                    return $this->redirect(
                        $this->generateUrl('user_manager').'#groups'
                    );
                }

                if(null !== $request->get('save_and_stay', null)){
                    return $this->redirectToRoute('user_group_edit', [
                        'id' => $entity->getId()
                    ]);
                }
            }

            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $manager->getErrors())
                ]));
        }

        $breadcrumbs = [
            [
                'href' => $this->redirectToRoute('dashboard'),
                'title' => $translator->trans('app.dashboard.callback'),
                'label' => $translator->trans('app.dashboard.title')
            ]
        ];

        if(null !== $callBackUrl){
            $breadcrumbs[] = [
                'href' => $callBackUrl,
                'title' => $translator->trans('member.manager.tabGroups'),
                'label' => $translator->trans('member.manager.tabGroups')
            ];
        }

        $breadcrumbs[] = [
            'label' => $translator->trans('user.group.edit.title')
        ];


        return $this->render('/user/group/edit.html.twig', array(
            'menuSelect' => 'user_manager',
            'formUserGroup' =>  $formHandler->getForm()->createView(),
            'breadcrumbs' => $breadcrumbs,
            'callBackUrl' => $callBackUrl
        ));
    }

    /**
     * @Route("/user/group/delete/{id}", name="user_group_delete", options={"expose"=true})
     */
    public function deleteAction(Request $request, $id)
    {
        if(!$request->isXmlHttpRequest()){
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');

        $this->denyAccessUnlessGranted('USER_GROUP_DELETE', null, $translator->trans('app.common.access_denied'));

        $userGroupManager = $this->get('user.manager.group');

        $entity = $userGroupManager->find($id);

        if(null === $entity){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('user.group.delete.deleteErrorMissingText')
            ];

            return new Response(
                (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json'));
        }

        if(!$userGroupManager->delete($entity)){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $userGroupManager->getErrors())
                ])
            ];

            return new Response(
                (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json'));
        }

        $array = [
            'code' => 'success',
            'message' => $translator->trans('user.group.delete.deleteSuccessText', ['%label%' => $entity->getLabel()])
        ];

        return new Response(
            (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json'));
    }
}
