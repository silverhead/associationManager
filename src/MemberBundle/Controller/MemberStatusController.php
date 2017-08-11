<?php

namespace MemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MemberStatusController extends Controller
{
    const ITEMS_PER_PAGE = 4;
    const PAGE_PARAMETER_NAME = 'pageTab2';

    /**
     * @Route("/member/status/list-part/{anchor}", name="member_status_list_part",  options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $anchor = null)
    {
        $page =  $request->get(self::PAGE_PARAMETER_NAME, 1);
        $currentRoute = $request->get('masterRoute', $request->get('_route'));

        $memberStatusManager = $this->get('member.manager.status');

        $results = $memberStatusManager->paginatedList(
            $page,
            self::ITEMS_PER_PAGE,
            self::PAGE_PARAMETER_NAME,
            $anchor,
            $currentRoute
        );

        $pageH = $this->get('app.handler.page_historical');

        $pageH->setCallbackUrl('member_status_edit',
            $this->generateUrl($currentRoute),
            [self::PAGE_PARAMETER_NAME => $page],
            $anchor
        );

        return $this->render('/member/status/statusList.html.twig', array(
            'results' => $results
        ));
    }

    /**
     * @Route("/member/status/json/save", name="member_status_save_json", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()){
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $id = $request->get('id', null);
        $label = $request->get('label', null);

        $translator = $this->get('translator');
        $statusManager = $this->get('member.manager.status');

        try{
            $statusManager->saveAjax($request);

            $array = [
                'code' => 'success',
                'message' => $translator->trans('member.status.edit.saveSuccessText')
            ];

        }
        catch(\Exception $e){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('app.common.errorComming', [
                    '%error%' => $e->getCode() . " : ".$e->getMessage()
                ])
            ];
        }

        return new Response(
            (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json'));
    }

    /**
     * @Route("/member/status/add/", name="member_status_add")
     * @Route("/member/status/edit/{id}", name="member_status_edit", defaults={"id": 0})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id = 0)
    {
        $memberStatusManager = $this->get('member.manager.status');

        $status = $memberStatusManager->find($id);
        if(null === $status){
            $status = $memberStatusManager->getNewEntity();
        }

        $formHandler = $this->get('member.form.handler.status');

        $formHandler->setForm($status);

        if($formHandler->process($request)){
            $translator = $this->get('translator');

            $status = $formHandler->getData();

            if($memberStatusManager->save($status)){
                $this->addFlash('success', $translator->trans('member.status.edit.saveSucessText'));

                if(null !== $request->get('save_and_leave', null)){
                    $pageH = $this->get('app.handler.page_historical');
                    $callBackUrl = $pageH->getCallbackUrl('member_status_edit');

                    if(null!== $callBackUrl){
                        return $this->redirect($callBackUrl);
                    }

                    return $this->redirect(
                        $this->generateUrl('member_manager').'#status'
                    );
                }

                if(null !== $request->get('save_and_stay', null)){
                    return $this->redirectToRoute('member_status_edit', [
                        'id' => $status->getId()
                    ]);
                }
            }

            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $memberStatusManager->getErrors())
                ]));
        }


        return $this->render('subscription/subscription/subscriptionEdit.html.twig', array(
            'formSubscription' => $formHandler->getForm()->createView()
        ));
    }

    /**
     * @Route("/member/status/delete/{id}", name="member_status_delete", options={"expose"=true})
     */
    public function deleteAction(Request $request, $id)
    {
        if(!$request->isXmlHttpRequest()){
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');
        $statusManager = $this->get('member.manager.status');

        $status = $statusManager->find($id);

        if(null === $status){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('member.status.delete.deleteErrorMissingText')
            ];

            return new Response(
                (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json'));
        }

        if(!$statusManager->delete($status)){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $statusManager->getErrors())
                ])
            ];

            return new Response(
                (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json'));
        }

        $array = [
            'code' => 'success',
            'message' => $translator->trans('member.status.delete.deleteSuccessText', ['%label%' => $status->getLabel()])
        ];

        return new Response(
            (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json'));
    }
}