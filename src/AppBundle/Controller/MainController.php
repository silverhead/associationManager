<?php

namespace AppBundle\Controller;

use AppBundle\QueryHelper\FilterQuery;
use AppBundle\QueryHelper\OrderQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
        return $this->render('main/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        $memberManager = $this->get('member.manager.member');
        $memberActifCount = $memberManager->getMemberNb(true);

        $subscriptionFeeManager = $this->get('member.manager.subscription_fee');
        $totalPayedFee = $subscriptionFeeManager->getTotalPaidFee();

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

        return $this->render('main/dashboard.html.twig', array(
            'memberActifCount' => $memberActifCount,
            'totalPayedFee' => $totalPayedFee,
            'latePaymentMemberList' => $latePaymentMemberList
        ));
    }
}
