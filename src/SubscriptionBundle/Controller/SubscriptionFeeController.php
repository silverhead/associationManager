<?php

namespace SubscriptionBundle\Controller;

use AppBundle\QueryHelper\FilterQuery;
use SubscriptionBundle\Form\Model\SubscriptionFeeListFilterModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
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

        $form = $this->getlistFilterForm($managerSubFee, $request);

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
            'order' => $ordersRequest,
            'filterForm' => $form->createView()
        ));
    }

    private function getlistFilterForm($manager, $request): FormInterface
    {
        $filterModel = $this->get('session')->get('subscription_fee_list_filter', new SubscriptionFeeListFilterModel());

        $formFilterHandler = $this->get('subscription.form.handler.subscription_fee_list_filter');
        $formFilterHandler->setForm($filterModel);

        if ($formFilterHandler->process($request)){
            $filterModel = $formFilterHandler->getData();
            $this->get('session')->set('subscription_fee_list_filter', $filterModel);
        }

        $fullName = $filterModel->getFullNameMember();

        if ("" !== $fullName){
            $fullNameArray = explode(" ", $fullName);
            foreach ($fullNameArray as $i => $value){
                $manager->addFilter(
                    new FilterQuery(" ( mber.firstName LIKE :fullName".$i." OR mber.lastName LIKE :fullName".$i.") ", "%".$value."%", FilterQuery::OPERATOR_EXP),
                    'fullName'.$i
                );
            }
        }

        $manager
            ->addFilter(
                new FilterQuery('sub.id', null !== $filterModel->getSubscription()?$filterModel->getSubscription()->getId():"", FilterQuery::OPERATOR_EQUAL)
            )
            ->addFilter(
                new FilterQuery('memberSubscriptionFee.startDate', $filterModel->getStartDate(), FilterQuery::OPERATOR_SUPERIOR_OR_EQUAL)
            )
            ->addFilter(
                new FilterQuery('memberSubscriptionFee.endDate', $filterModel->getEndDate(), FilterQuery::OPERATOR_INFERIOR_OR_EQUAL)
            )
            ->addFilter(
                new FilterQuery('memberSubscriptionFee.paid', null !== $filterModel->isPaid()?$filterModel->isPaid():null, FilterQuery::OPERATOR_EQUAL)
            )
        ;

        return $formFilterHandler->getForm();
    }
}
