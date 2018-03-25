<?php

namespace MemberBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MemberBundle\Entity\MemberSubscriptionFee;
use MemberBundle\Entity\MemberSubscriptionHistorical;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use MemberBundle\Entity\MemberStatusHistorical;

class MemberController extends Controller
{    
    /**
     * @Route("/members/manager", name="members_manager")
     */
    public function indexAction()
    {
        $memberManager = $this->get('member.manager.member');
        
        return $this->render('member/membersManager.html.twig', array(
            'menuSelect' => 'members_manager',
            'nbMember' => $memberManager->getMemberNb()
        ));
    }

    /**
     * @Route("/member/member/list-part/{anchor}", name="member_member_list_part",  options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $anchor = null)
    {
        $memberManager = $this->get('member.manager.member');

        $orders = $request->get('orders', array(
            'lastName' => 'asc',
            'firstName' => 'asc',
            'status' => '',
            'subscription' => '',
//             'subscriptionDateEnd' => 'asc',
        ));

        $memberManager->setPaginatorOrders($orders);
        
        $members = $memberManager->paginatedList();
        
        return $this->render('member/member/list.html.twig', array(
            'members' => $members,
            'order' => $orders
        ));
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
            $entity->setAvatar(
                new File($this->getParameter('avatars_directory') . $entity->getAvatar())
            );
        } 
        else{
            $entity = $manager->getNewEntity();
            
            $status = new MemberStatusHistorical();
            $status->setMember($entity)
                ->setStartDate(new \DateTime())
            ;
            
            $entity->addStatus($status);
        }

        $pageH = $this->get('app.handler.page_historical');
        $callBackUrl = $this->generateUrl('members_manager');//$pageH->getCallbackUrl('member_edit');
        
        $formHandler = $this->get('member.form.handler.member');
        $formHandler->setForm($entity);

        if($formHandler->process($request)){
            
            $entity = $formHandler->getData();
            
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
            'breadcrumbs' => $breadcrumbs
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
}
