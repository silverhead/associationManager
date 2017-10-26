<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    const ITEMS_PER_PAGE = 4;
    const PAGE_PARAMETER_NAME = 'pageTab3';


    /**
     * @Route("/user/manager", name="user_manager")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('user/manager.html.twig', array());
    }


    /**
     * @Route("/user/list-part/{anchor}", name="user_list_part",  options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $anchor = null)
    {
        $page =  $request->get(self::PAGE_PARAMETER_NAME, 1);
        $currentRoute = 'user_manager';

        //dump($request);exit();
        
        $userManager = $this->get('user.manager.user');
        
        $orders = $request->get('paginatorOrders', array(
            'username' => 'asc',
            'email' => '',
            'group' => ''
        ));

        
        
        $userManager->setPaginatorOrders($orders);
        
        $results = $userManager->paginatedList(
            $page,
            self::ITEMS_PER_PAGE,
            self::PAGE_PARAMETER_NAME,
            $anchor,
            $currentRoute
        );

        $pageH = $this->get('app.handler.page_historical');

        $pageH->setCallbackUrl('user_edit',
            $this->generateUrl($currentRoute),
            [self::PAGE_PARAMETER_NAME => $page],
            $anchor
        );

        return $this->render('/user/user/list.html.twig', array(
            'results' => $results,
            'order' => $orders
        ));
    }

    /**
     * @Route("/user/add", name="user_add")
     * @Route("/user/edit/{id}", name="user_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id = null)
    {
        $manager = $this->get('user.manager.user');
        $formHandler = $this->get('user.form.handler.user');

        $entity = $manager->find($id);
        if(null === $entity){
            $entity = $manager->getNewEntity();
        }

        $pageH = $this->get('app.handler.page_historical');
        $callBackUrl = $pageH->getCallbackUrl('user_edit');

        $translator = $this->get('translator');

        $formHandler->setForm($entity);

        if($formHandler->process($request)){

            $form = $formHandler->getForm();

            $entity->setFirstName($form['firstName']->getData());
            $entity->setLastName($form['lastName']->getData());
            $entity->setUsername($form['username']->getData());
            $entity->setEmail($form['email']->getData());

            if(!empty($form['password']->getData())){
                $encoder = $this->get('security.password_encoder');

                $entity->setSalt(uniqid());

                $entity->setPassword(
                    $encoder->encodePassword($entity, $form['password']->getData())
                );
            }

            if($manager->save($entity)){
                $this->addFlash('success', $translator->trans('user.user.edit.saveSuccessText'));

                if(null !== $request->get('save_and_leave', null)){
                    if(null!== $callBackUrl){
                        return $this->redirect($callBackUrl);
                    }

                    return $this->redirect(
                        $this->generateUrl('user_manager').'#users'
                    );
                }

                if(null !== $request->get('save_and_stay', null)){
                    return $this->redirectToRoute('user_edit', [
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
                'title' => $translator->trans('user.manager.tabUsers'),
                'label' => $translator->trans('user.manager.tabUsers')
            ];
        }

        $breadcrumbs[] = [
            'label' => $translator->trans('user.user.edit.title')
        ];


        return $this->render('/user/user/edit.html.twig', array(
            'formUser' =>  $formHandler->getForm()->createView(),
            'breadcrumbs' => $breadcrumbs,
            'callBackUrl' => $callBackUrl
        ));
    }
}
