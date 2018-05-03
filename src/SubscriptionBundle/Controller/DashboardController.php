<?php

namespace SubscriptionBundle\Controller;

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

    public function totalSubscribersListAction()
    {
        $subscriptionFeeManager = $this->get('member.manager.subscription_fee');
        $totalPayedFee = $subscriptionFeeManager->getTotalPaidFee();

        return $this->renderView(':subscription/subscription/dashboard:totalSubscribersList.html.twig', array(
            'totalPayedFee' => $totalPayedFee
        ));
    }
}