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

            if($periodicityManager->save($periodicity)){

                $this->addFlash('success', "Periode enregistrée avec succés !");

                if(null !== $request->get('save_and_leave', null)){
                    return $this->redirect(
                        $this->generateUrl('subscription_manager').'#periodicities'
                    );
                }

                if(null !== $request->get('save_and_stay', null)){
                    return $this->redirectToRoute('periodicity_edit', [
                        'id' => $periodicity->getId()
                    ]);
                }
            }

            $this->addFlash(
                'error',
                "Une erreur est intervenue !<br />" . implode('<br />', $periodicityManager->getErrors()));
        }

        return $this->render('/subscription/periodicity/periodicityEdit.html.twig', array(
            'formPeriodicity' => $formHandler->getForm()->createView()
        ));
    }

    public function listAction(Request $request)
    {
        $periodicityManager = $this->get('app.subscription.manager.periodicity');

        $results = $periodicityManager->paginatedList(
            $request->get("page", 1)
        );

        return $this->render('/subscription/periodicity/periodicityList.html.twig', array(
            'results' => $results
        ));
    }
}
