<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/subscription/add/", name="subscription_add")
     * @Route("/subscription/edit/{id}", name="subscription_edit", defaults={"id": 0})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id = 0)
    {
        $abonnement = [
            'libelle' => null,
            'cout' => null,
            'duree' => null,
            'periodicites' => array(),
            'statut' => null
        ];

        if($id > 0){
            $abonnement = [
                'libelle' => 'Abonnement premium 1 an',
                'cout' => 240.00,
                'duree' => 365,
                'periodicites' => array(1,2,3),
                'statut' => 1
            ];
        }

        return $this->render('subscription/subscriptionEdit.html.twig', array(
            'abonnement' => $abonnement
        ));
    }
}
