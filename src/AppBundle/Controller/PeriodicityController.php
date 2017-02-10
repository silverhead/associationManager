<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PeriodicityController extends Controller
{
    /**
     * @Route("/periodicity_edit/{id}", name="periodicity_edit")
     * @Route("/periodicity_add", name="periodicity_add")
     * @param Request $request
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id = null)
    {
        $periodicity = (object) [
            'label' => '',
            'duration' => '',
            'status' => -1,
        ];

        if(null !== $id){
            $periodicity = (object) [
                'label' => '1 mois',
                'duration' => 30,
                'status' => 1,
            ];
        }


        return $this->render('/subscription/periodicity/periodicityEdit.html.twig', array(
            'periodicity' => $periodicity
        ));
    }
}
