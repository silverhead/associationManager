<?php

namespace MemberBundle\Controller;

use AppBundle\QueryHelper\FilterQuery;
use AppBundle\QueryHelper\OrderQuery;
use MemberBundle\Entity\Member;
use MemberBundle\Entity\MemberSubscriptionHistorical;
use MemberBundle\Form\Model\MemberImportModel;
use MemberBundle\Form\Model\MemberListFilterModel;
use MemberBundle\Form\Type\MemberImportFormType;
use MemberBundle\Manager\MemberImportManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MemberBundle\Entity\MemberStatusHistorical;
use Symfony\Component\HttpFoundation\Response;

class MemberController extends Controller
{    
    /**
     * @Route("/members/manager", name="members_manager")
     */
    public function indexAction(Request $request)
    {
        $translator = $this->get('translator');

        if(
            (!$this->isGranted("MEMBER_MEMBER_VIEW"))
            &&
            (!$this->isGranted("MEMBER_STATUS_VIEW"))
        ){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));

            return $this->redirect(
                $this->generateUrl('dashboard')
            );
        }

        $memberManager = $this->get('member.manager.member');

        $memberListTpl = $this->memberlist($request, "");
        $statusListTpl = $this->get('member.controller.status')->listAction($request, "status");
        $groupsListTpl = $this->get('member.controller.groups')->memberGroupList($request, "groups");

        $settingTpl = $this->get('member.controller.setting')->settingAction($request);
        $settingEmailTpl = $this->get('member.controller.setting')->emailSettingAction($request);

        return $this->render('member/membersManager.html.twig', array(
            'menuSelect' => 'members_manager',
            'nbTotalMembers' => $memberManager->getMemberNb(),
            'nbTotalActiveMembers' => $memberManager->getMemberNb(true),
            'memberListTpl' => $memberListTpl,
            'statusListTpl' => $statusListTpl,
            'groupsListTpl' => $groupsListTpl,
            'settingTpl' => $settingTpl,
            'settingEmailTpl' => $settingEmailTpl
        ));
    }

    /**
     * @Route("/member/member/list-part/{anchor}", name="member_member_list_part",  options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $anchor = null)
    {
        return new Response( $this->memberList($request, $anchor));
    }

    public function memberList(Request $request, $anchor = null)
    {
        $memberManager = $this->get('member.manager.member');
        $memberManager->activateCache('memberList');

        $ordersRequest = $this->getListOrder($memberManager, $request);
        $formFilterHandler = $this->getlistFilterForm($memberManager, $request);

        $members = $memberManager->paginatedList(
            $request->query->getInt('pageMemberList', 1),
            10,
            'pageMemberList',
            $anchor,
            $request->get('master_route', 'members_manager')
            );

        // TODO : must be optimised
        $membersIdList = array();
        foreach($members as $member){
            $membersIdList[] = $member->getId();
        }

        $feeManager = $this->get('member.manager.subscription_fee');

        $this->defineTheLatePaymentMembers($feeManager, $membersIdList, $members);
        $this->defineTheMembersHaveSoonPayment($feeManager, $membersIdList, $members);

        return $this->renderView('member/member/list.html.twig', array(
            'members' => $members,
            'order' => $ordersRequest,
            'filter' => $formFilterHandler->getForm()->createView()
        ));
    }

    private function defineTheLatePaymentMembers($feeManager, $membersIdList, $members)
    {
        $latePaymentFeeList = $feeManager->getLatePaymentFeeByMemberIdList($membersIdList);
        $latePaymentmemberList = array_column($latePaymentFeeList, "id");
        // TODO : must be optimised
        foreach($members as $member){
            if (in_array($member->getId(), $latePaymentmemberList)){
                $member->setIsLatePayment(true);
            }
        }
    }

    private function defineTheMembersHaveSoonPayment($feeManager, $membersIdList, $members)
    {
        $now = new \DateTime();
        $delayDayMax = (clone $now)->add(new \DateInterval("P20D"));
        $soonPaymentFeeList = $feeManager->getSoonFeeNewPaymentListByMemberIdList($now,$delayDayMax, $membersIdList);
        $soonFeeMemberList = array_column($soonPaymentFeeList, "id");
        // TODO : must be optimised
        foreach($members as $member){
            if (in_array($member->getId(), $soonFeeMemberList)){
                $member->setHaveNewFeeComingSoon(true);
            }
        }
    }

    private function getListOrder($memberManager, $request)
    {
        $ordersRequest = $request->get('orders',  $memberManager->getArrayOrdersInCacheByKey(
            array(
                "lastName" => "ASC",
                "firstName" => "ASC",
                "status" => "",
                "subscription" => "",
                "subscriptionDateEnd" => "",
            )
        ));

        //dump($ordersRequest);exit();

        $memberManager
            ->addOrder(
                new OrderQuery("m.lastName" , $ordersRequest['lastName']),
                'lastName'
            )
            ->addOrder(
                new OrderQuery("m.firstName" , $ordersRequest['firstName']),
                'firstName'
            )
            ->addOrder(
                new OrderQuery("mshStatus.label" , $ordersRequest['status']),
                'status'
            )
            ->addOrder(
                new OrderQuery("subscription.label" , $ordersRequest['subscription']),
                'subscription'
            )
            ->addOrder(
                new OrderQuery("subscriptions.endDate" , $ordersRequest['subscriptionDateEnd']),
                'subscriptionDateEnd'
            )
        ;

        return $ordersRequest;
    }

    private function getlistFilterForm($memberManager, $request)
    {
        $filterModel = $this->get('session')->get('member_list_filter', new MemberListFilterModel());

        $formFilterHandler = $this->get('member.form.handler.member_list_filter');
        $formFilterHandler->setForm($filterModel);

        if ($formFilterHandler->process($request)){
            $filterModel = $formFilterHandler->getData();
            $this->get('session')->set('member_list_filter', $filterModel);
        }

        $memberManager
            ->addFilter(
                new FilterQuery('m.lastName', $filterModel->getLastName(), FilterQuery::OPERATOR_LIKE_RIGHT)
            )
            ->addFilter(
                new FilterQuery('m.firstName', $filterModel->getFirstName(), FilterQuery::OPERATOR_LIKE_RIGHT)
            )
            ->addFilter(
                new FilterQuery('mshStatus.id', null !== $filterModel->getStatus()?$filterModel->getStatus()->getId():"", FilterQuery::OPERATOR_EQUAL)
            )
            ->addFilter(
                new FilterQuery('subscription.id', null !== $filterModel->getSubscription()?$filterModel->getSubscription()->getId():"", FilterQuery::OPERATOR_EQUAL)
            )
            ->addFilter(
                new FilterQuery('m.active', null !== $filterModel->isActive()?$filterModel->isActive():null, FilterQuery::OPERATOR_EQUAL)
            )
        ;

        $now = new \DateTime();
        $delayDayMax = (clone $now)->add(new \DateInterval("P20D"));
        $feeManager = $this->get('member.manager.subscription_fee');

        if ($filterModel->isOnlyNewFeeComingSoon()){
            $membersFeeIdList = $feeManager->getSoonFeeNewPaymentMemberIdList($now, $delayDayMax, 1000);

            if(count($membersFeeIdList) == 0){
                $membersFeeIdList[] = 0;
            }
            $memberManager->addFilter(
                new FilterQuery('m.id', $membersFeeIdList, FilterQuery::OPERATOR_IN)
            );
        }

        if ($filterModel->isOnlyLatePaymentMember()){
            $membersFeeIdList = $feeManager->getLatePaymentFeeMemberIdList();
            if(count($membersFeeIdList) == 0){
                $membersFeeIdList[] = 0;
            }
            $memberManager->addFilter(
                new FilterQuery('m.id', $membersFeeIdList, FilterQuery::OPERATOR_IN)
            );
        }

        return $formFilterHandler;
    }

    /**
     * @Route("/members/your_profile", name="member_profile");
     */
    public function editProfileAction(Request $request)
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

        $manager = $this->get('member.manager.member');
        $entity = $manager->find($user->getId());

        $formHandler = $this->get('member.form.handler.member');
        $formHandler->setForm($entity, true);

        if($formHandler->process($request)){
            $entity = $formHandler->getData();

            if($manager->save($entity)){
                $this->addFlash('success', $translator->trans('member.member.profile.saveSuccessText'));
            }
            else{
                $this->addFlash(
                    'error',
                    $translator->trans('app.common.errorComming', [
                        '%error%' => '<br />' . implode('<br />', $manager->getErrors())
                    ]));
            }

            return $this->redirectToRoute('member_profile');
        }

        $breadcrumbs = [
            [
                'href' => $this->generateUrl('dashboard'),
                'title' => $translator->trans('app.dashboard.callback'),
                'label' => $translator->trans('app.dashboard.title')
            ],
            ['label' => $translator->trans('member.member.profile.title')]
        ];

        return $this->render('member/member/profile.html.twig', [
            'menuSelect' => 'members_manager',
            'form' => $formHandler->getForm()->createView(),
            'callBackUrl' => $this->generateUrl('dashboard'),
            'breadcrumbs' => $breadcrumbs,
            'member' => $entity
        ]);
    }

    /**
     * @Route("/members/edit/{id}", name="member_edit", requirements={"id": "\d+"}, defaults={"id": 0});
     */
    public function editMemberAction(Request $request, $id = 0)
    {
        $translator = $this->get('translator');

        if(
            (!$this->isGranted("MEMBER_MEMBER_EDIT") && $id > 0)
            ||
            (!$this->isGranted("MEMBER_MEMBER_CREATE") && $id == 0)
        ){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));
            
                return $this->redirect(
                    $this->generateUrl('members_manager').'#members'
                    );
        }
        
        $manager = $this->get('member.manager.member');
        
        if($id > 0){
            $entity = $manager->find($id);
        } 
        else{
            /**
             * @var Member
             */
            $entity = $manager->getNewEntity();
            
            $status = new MemberStatusHistorical();
            $status->setMember($entity)
                ->setStartDate(new \DateTime())
            ;
            
            $entity->addStatusHistorical($status);
        }

        $pageH = $this->get('app.handler.page_historical');
        $callBackUrl = $this->generateUrl('members_manager');//$pageH->getCallbackUrl('member_edit');
        
        $formHandler = $this->get('member.form.handler.member');
        $formHandler->setForm($entity);

        if($formHandler->process($request)){
            
            $entity = $formHandler->getData();
            $entity->setUpdatedAt(new \DateTime());

            if($manager->save($entity)){

                $this->addFlash('success', $translator->trans('member.member.edit.saveSuccessText'));
                
                if(null !== $request->get('save_and_leave', null)){
                    if(null!== $callBackUrl){
                        return $this->redirect($callBackUrl);
                    }
                    
                    return $this->redirect(
                        $this->generateUrl('members_manager').'#members'
                        );
                }
                
                if(null !== $request->get('save_and_stay', null)){
                    return $this->redirectToRoute('member_edit', [
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
                'title' => $translator->trans('member.manager.tabMembers'),
                'label' => $translator->trans('member.manager.tabMembers')
            ];
        }
        
        $breadcrumbs[]  =  ['label' => $translator->trans('member.member.edit.title')];
        
        return $this->render('member/member/edit.html.twig', [
            'menuSelect' => 'members_manager',
            'form' => $formHandler->getForm()->createView(),
            'callBackUrl' => $callBackUrl,
            'breadcrumbs' => $breadcrumbs,
            'member' => $entity
         ]);
    }

    /**
     * @Route("/members/view/{id}", name="member_view", requirements={"id": "\d+"});
     */
    public function viewMemberAction(Request $request, $id)
    {
        $translator = $this->get('translator');

        if(!$this->isGranted("MEMBER_MEMBER_VIEW")){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));

            return $this->redirect(
                $this->generateUrl('members_manager').'#members'
            );
        }

        $manager = $this->get('member.manager.member');
        $member = $manager->find($id);

        $subcriptionManager = $this->get('member.manager.subscription_historical');
        $subscriptions = $subcriptionManager->getListByMember($member, 5);

        if(null === $member){
            $this->addFlash(
                'error',
                $translator->trans('member.member.memberNotFound'));

            return $this->redirect(
                $this->generateUrl('members_manager').'#members'
            );
        }

        $pageH = $this->get('app.handler.page_historical');

        $pageH->setCallbackUrl('subscription_fee_save',
            $this->generateUrl('member_view', ['id' => $id]),
            [],
            'subscriptionFee',
            'member_view'
        );

        $formHandler = $this->get('member.form.handler.subscription_historical');
        $formHandler->setForm(new MemberSubscriptionHistorical());

        $breadcrumbs = [
            [
                'href' => $this->redirectToRoute('dashboard'),
                'title' => $translator->trans('app.dashboard.callback'),
                'label' => $translator->trans('app.dashboard.title')
            ],
            [
                'href' =>  $this->generateUrl('members_manager').'#members',
                'title' => $translator->trans('member.manager.tabMembers'),
                'label' => $translator->trans('member.manager.tabMembers')
            ],
            ['label' => $translator->trans('member.member.view.title', ['{firstName}' => $member->getFirstName(), '{lastName}' => $member->getLastName()])]
        ];

        return $this->render('member/member/view.html.twig', [
            'member' => $member,
            'subscriptions' => $subscriptions,
            'breadcrumbs' => $breadcrumbs,
            'menuSelect' => 'member_manager',
            'formSub' => $formHandler->getForm()->createView()
        ]);
    }

    /**
     * @Route("members/import", name="member_import")
     */
    public function importAction(Request $request)
    {
        $root = $this->getParameter('kernel.root_dir');

        $pathImport = $root.DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR."import";

        $translator = $this->get('translator');

        $form = $this->createForm(MemberImportFormType::class, new MemberImportModel() );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $model = $form->getData();

            $model->getFile()->move($pathImport, $model->getFile()->getFilename());
            $pathFile = $pathImport.DIRECTORY_SEPARATOR.$model->getFile()->getFilename();

            $settingManager = $this->get('app.manager.setting');

            $memberImportManager = new MemberImportManager($translator, $this->get('validator'), $settingManager);

            $memberImports = array();
            if ($memberImportManager->import($pathFile)){
                $memberImports = $memberImportManager->getData();
            }
            else{
                $errorMessage = "";

                foreach($memberImportManager->getErrors() as $error){
                    $errorMessage .= $errorMessage!=''?'<br>':'';
                    $errorMessage .= $error;
                }

                $this->addFlash('error', $errorMessage);
            }
        }

        $breadcrumbs = [
            [
                'href' => $this->redirectToRoute('dashboard'),
                'title' => $translator->trans('app.dashboard.callback'),
                'label' => $translator->trans('app.dashboard.title')
            ],
            [
                'href' =>  $this->generateUrl('members_manager').'#members',
                'title' => $translator->trans('member.manager.tabMembers'),
                'label' => $translator->trans('member.manager.tabMembers')
            ],
            ['label' => $translator->trans('member.member.import.title')]
        ];

        return $this->render('member/member/import.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'form' => $form->createView()
        ]);
    }
}
