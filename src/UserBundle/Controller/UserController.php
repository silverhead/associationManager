<?php

namespace UserBundle\Controller;

use AppBundle\QueryHelper\FilterQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\QueryHelper\OrderQuery;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Form\Model\UserListFilterModel;

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
        $translator = $this->get('translator');

        if(
            (!$this->isGranted("USER_USER_VIEW"))
        ){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));

            return $this->redirect(
                $this->generateUrl('dashboard')
            );
        }

        return $this->render('user/manager.html.twig', array(
            'menuSelect' => 'user_manager'
        ));
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

        $userManager = $this->get('user.manager.user');

        $userManager->activateCache('userList');

        $orders = $request->get('orders', $userManager->getArrayOrdersInCacheByKey(array(
        'username' => 'asc',
            'email' => '',
            'group' => ''
        )));

        $userManager
            ->addOrder(
                new OrderQuery("u.username" , $orders['username']),
                'username'
            )
            ->addOrder(
                new OrderQuery("u.email" , $orders['email']),
                'email'
            )
            ->addOrder(
                new OrderQuery("g.label" , $orders['group']),
                'group'
            )
        ;

        $formFilterHandler = $this->getlistFilterForm($userManager, $request);

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
            'menuSelect' => 'user_manager',
            'results' => $results,
            'order' => $orders,
            'filter' => $formFilterHandler->getForm()->createView()
        ));
    }

    private function getlistFilterForm($ListManager, $request)
    {
        $filterModel = $this->get('session')->get('user_list_filter', new UserListFilterModel());

        $formFilterHandler = $this->get('user.form.handler.user_list_filter');
        $formFilterHandler->setForm($filterModel);

        if ($formFilterHandler->process($request)){
            $filterModel = $formFilterHandler->getData();
            $this->get('session')->set('user_list_filter', $filterModel);
        }

        $ListManager
            ->addFilter(
                new FilterQuery('u.username', $filterModel->getUsername(), FilterQuery::OPERATOR_LIKE_BETWEEN)
            )
            ->addFilter(
                new FilterQuery('u.email', $filterModel->getEmail(), FilterQuery::OPERATOR_LIKE_BETWEEN)
            )
            ->addFilter(
                new FilterQuery('g.id', null !== $filterModel->getGroup()?$filterModel->getGroup()->getId():"", FilterQuery::OPERATOR_EQUAL)
            )
            ->addFilter(
                new FilterQuery('u.active', null !== $filterModel->isActive()?$filterModel->isActive():null, FilterQuery::OPERATOR_EQUAL)
            )
        ;

        return $formFilterHandler;
    }

    /**
     * @Route("/user/your_profile", name="user_profile")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request)
    {
        $translator = $this->get('translator');

        $user = $this->getUser();

        if(null === $user){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));

            return $this->redirect(
                $this->generateUrl('dashboard')
            );
        }

        $manager = $this->get('user.manager.user');
        $formHandler = $this->get('user.form.handler.user');

        $entity = $manager->find($user->getId());

        $formHandler->setForm($entity, true);

        if($formHandler->process($request)){
            $entity = $formHandler->getData();

            if($manager->save($entity)){
                $this->addFlash('success', $translator->trans('user.user.profile.saveSuccessText'));
            }
            else{
                $this->addFlash(
                    'error',
                    $translator->trans('app.common.errorComming', [
                        '%error%' => '<br />' . implode('<br />', $manager->getErrors())
                ]));
            }

            return $this->redirectToRoute('user_profile');
        }

        $breadcrumbs = [
            [
                'href' => $this->redirectToRoute('dashboard'),
                'title' => $translator->trans('app.dashboard.callback'),
                'label' => $translator->trans('app.dashboard.title')
            ]
        ];

        $breadcrumbs[] = [
            'label' => $translator->trans('user.user.profile.title')
        ];

        return $this->render('/user/user/profile.html.twig', array(
            'menuSelect' => 'user_manager',
            'formUser' =>  $formHandler->getForm()->createView(),
            'breadcrumbs' => $breadcrumbs,
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
        $translator = $this->get('translator');

        if(
            (!$this->isGranted("USER_USER_EDIT") && $id > 0 )
            ||
            (!$this->isGranted("USER_USER_CREATE") && $id == 0)
        ){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));

            return $this->redirect(
                $this->generateUrl('dashboard')
            );
        }

        $manager = $this->get('user.manager.user');
        $formHandler = $this->get('user.form.handler.user');

        $entity = $manager->find($id);
        if(null === $entity){
            $entity = $manager->getNewEntity();
        }

        $pageH = $this->get('app.handler.page_historical');
        $callBackUrl = $pageH->getCallbackUrl('user_edit');

        $formHandler->setForm($entity);

        if($formHandler->process($request)){
            $entity = $formHandler->getData();

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
            'menuSelect' => 'user_manager',
            'formUser' =>  $formHandler->getForm()->createView(),
            'breadcrumbs' => $breadcrumbs,
            'callBackUrl' => $callBackUrl
        ));
    }
}
