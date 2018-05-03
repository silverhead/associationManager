<?php

namespace MemberBundle\Controller;

use AppBundle\QueryHelper\FilterQuery;
use AppBundle\QueryHelper\OrderQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DashboardController extends Controller
{
    public function __construct(ContainerInterface $container = null)
    {
        $this->setContainer($container);
    }

    public function getAction(string $actionName)
    {
        if(method_exists($this, $actionName)){
            return $this->$actionName();
        }
        else{
            throw new \Exception("The action \"".$actionName."\" not exist in the class \”".self::class."\“!");
        }

    }

    public function latePaymentMemberListAction()
    {
        $memberManager = $this->get('member.manager.member');
        $memberActifCount = $memberManager->getMemberNb(true);



        $memberManager->addFilter(
            new FilterQuery("fees.startDate", new \DateTime(), "<"),
            'feeStartDate'
        )
        ->addFilter(
            new FilterQuery("fees.paid", false, "="),
            'feesPaid'
        );

        $memberManager->addOrder(
            new OrderQuery('fees.startDate', OrderQuery::DESC),
            'feeStartDate'
        );

        $latePaymentMemberList = $memberManager->getList(0,10);

        return $this->renderView(
            ':member/member/dashboard:latePaymentMembersList.html.twig',array(
            'memberActifCount' => $memberActifCount,
            'latePaymentMemberList' => $latePaymentMemberList
        ));
    }
}