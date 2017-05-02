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
        $periodicityManager = $this->get('app.subscription.manager.periodicity');
        $formHandler = $this->get('app.subscription.form.handler.periodicity');

        $periodicity = $periodicityManager->find($id);
        $formHandler->setForm($periodicity);

        if($formHandler->process($request)){
            $periodicity = $formHandler->getData();

            $periodicityManager->save($periodicity);
        }

        return $this->render('/subscription/periodicity/periodicityEdit.html.twig', array(
            'formPeriodicity' => $formHandler->getForm()->createView()
        ));
    }
}
