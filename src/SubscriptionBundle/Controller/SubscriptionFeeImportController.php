<?php

namespace SubscriptionBundle\Controller;


use SubscriptionBundle\Form\Model\SubscriptionFeeImportModel;
use SubscriptionBundle\Form\Type\SubscriptionFeeImportFormType;
use SubscriptionBundle\Manager\SubscriptionFeeImportManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionFeeImportController extends Controller
{
    /**
     * @Route("subscription/fee/import", name="subscription_fee_import")
     */
    public function importAction(Request $request)
    {
        $root = $this->getParameter('kernel.root_dir');
        $pathImport = $root.DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR."import";
        $translator = $this->get('translator');
        $form = $this->createForm(SubscriptionFeeImportFormType::class, new SubscriptionFeeImportModel() );
        $form->handleRequest($request);
        $subscriptionFeeImports = array();
        if ($form->isSubmitted() && $form->isValid()){
            $model = $form->getData();
            $model->getFile()->move($pathImport, $model->getFile()->getFilename());
            $pathFile = $pathImport.DIRECTORY_SEPARATOR.$model->getFile()->getFilename();

            $subscriptionImportManager = new SubscriptionFeeImportManager(
                $this->getDoctrine()->getManager(),
                $translator,
                $this->get('validator')
            );

            if ($subscriptionImportManager->import($pathFile)){
                $subscriptionFeeImports = $subscriptionImportManager->getData();
            }
            if (count($subscriptionImportManager->getErrors()) > 0 ){
                $errorMessage = "";
                foreach($subscriptionImportManager->getErrors() as $error){
                    $errorMessage .= $errorMessage!=''?'<br>':'';
                    $errorMessage .= $error;
                }
                $this->addFlash('error', $errorMessage);
            }
        }
        $breadcrumbs = [
            [
                'href' => $this->redirectToRoute('dashboard'),
                'title' => $translator->trans('app.dashboard.callback'),
                'label' => $translator->trans('app.dashboard.title')
            ],
            [
                'href' =>  $this->generateUrl('subscription_manager').'#subscriptionFees',
                'title' => $translator->trans('subscription.manager.tabSubscriptionsFee'),
                'label' => $translator->trans('subscription.manager.tabSubscriptionsFee')
            ],
            ['label' => $translator->trans('subscription.fee.import.title')]
        ];
        return $this->render('subscription/subscription/fee/import.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'subscriptionFeeImports' => $subscriptionFeeImports,
            'form' => $form->createView()
        ]);
    }
}