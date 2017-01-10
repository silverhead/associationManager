<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SubscriptionController extends Controller
{
    /**
     * @Route("/subscription/manager", name="subscription_manager")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerAction()
    {
        return $this->render('subscription/subscriptionManager.html.twig', array());
    }

    /**
     * @Route("/subscription/edit", name="subscription_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction()
    {
        return $this->render('subscription/subscriptionEdit.html.twig', array());
    }
}
