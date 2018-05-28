<?php

namespace SubscriptionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\QueryHelper\OrderQuery;

class SubscriptionFeeController extends Controller
{
    /**
     * @Route("/subscription/fees/list_part/{anchor}", name="subscription_fees_list_part", options = { "expose" = true });
     */
    public function listAction(Request $request)
    {
        $pageParamName = 'subFeePage';
        $page = $request->get($pageParamName, 1);

        $managerSubFee = $this->get('member.manager.subscription_fee');

        $managerSubFee->activateCache('memberList');

        $ordersRequest = $request->get('orders',  $managerSubFee->getArrayOrdersInCacheByKey(
            array(
                "period" => "DESC",
                "fullName" => "ASC",
                "subscription" => "ASC",
            )
        ));

        $managerSubFee
            ->addOrder(
                new OrderQuery("memberSubscriptionFee.endDate", $ordersRequest['period']),
                'period'
            )
            ->addOrder(
                new OrderQuery("CONCAT(mber.lastName,' ',mber.firstName)", $ordersRequest['fullName']),
                'fullName'
            )
            ->addOrder(
                new OrderQuery("sub.label", $ordersRequest['subscription']),
                'subscription'
            )
        ;

        $subscriptionFees = $managerSubFee->paginatedList($page, 20, $pageParamName);

        $pageH = $this->get('app.handler.page_historical');

        $pageH->setCallbackUrl('subscription_fee_save',
            $this->generateUrl('subscription_manager'),
            [$pageParamName => $page],
            'subscriptionFees',
            'subscription_manager'
        );

        return $this->render('subscription/subscription/fee/subscriptionFee.html.twig', array(
            'fees' => $subscriptionFees,
            'order' => $ordersRequest
        ));
    }
}
