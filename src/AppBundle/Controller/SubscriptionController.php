<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionController extends Controller
{
    const ITEMS_PER_PAGE = 4;
    const PAGE_PARAMETER_NAME = 'pageTab1';

    /**
     * @Route("/subscription/manager", name="subscription_manager")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managerAction(Request $request, $anchor = null)
    {
//        $page =  $request->get(self::PAGE_PARAMETER_NAME, 1);
//        $currentRoute = $request->get('_route');
//
//        $subscriptionManager = $this->get('app.subscription.manager.subscription');
//
//        $results = $subscriptionManager->paginatedList($page, self::ITEMS_PER_PAGE, self::PAGE_PARAMETER_NAME);
//
//        $pageH = $this->get('app.handler.page_historical');
//
//        $pageH->setCallbackUrl('subscription_subscription_edit',
//            $this->generateUrl($currentRoute),
//            [self::PAGE_PARAMETER_NAME => $page],
//            $anchor
//        );


        return $this->render('subscription/subscriptionManager.html.twig', array(
            'menuSelect' => 'subscription_manager',
//            'results' => $results
        ));
    }


    /**
     * @Route("/subscription/subscription/list-part", name="subscription_subscription_list_part",  options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()//Request $request, $anchor = null
    {
        $page =  1;//$request->get(self::PAGE_PARAMETER_NAME, 1);
        $currentRoute = 'subscription_manager';//$request->get('_route');

        $subscriptionManager = $this->get('app.subscription.manager.subscription');

        $results = null;// $subscriptionManager->paginatedList($page, self::ITEMS_PER_PAGE, self::PAGE_PARAMETER_NAME);

        $pageH = $this->get('app.handler.page_historical');

        $pageH->setCallbackUrl('subscription_subscription_edit',
            $this->generateUrl($currentRoute),
            [self::PAGE_PARAMETER_NAME => $page]
//            $anchor
        );

        return $this->render('/subscription/subscription/subscriptionList.html.twig', array(
            'results' => $results
        ));
    }

    /**
     * @Route("/subscription/add/", name="subscription_add")
     * @Route("/subscription/edit/{id}", name="subscription_edit", defaults={"id": 0})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id = 0)
    {
        $subscriptionManager = $this->get('app.subscription.manager.subscription');

        $subscription = $subscriptionManager->find($id);
        if(null === $subscription){
            $subscription = $subscriptionManager->getNewEntity();
        }

        $formHandler = $this->get('app.subscription.form.handler.subscription');

        $formHandler->setForm($subscription);

        if($formHandler->process($request)){
            $translator = $this->get('translator');

            $subscription = $formHandler->getData();

            if($subscriptionManager->save($subscription)){
                $this->addFlash('success', $translator->trans('app.subscription.subscription.edit.saveSucessText'));

                if(null !== $request->get('save_and_leave', null)){
                    $pageH = $this->get('app.handler.page_historical');
                    $callBackUrl = $pageH->getCallbackUrl('subscription_subscription_edit');

                    if(null!== $callBackUrl){
                        return $this->redirect($callBackUrl);
                    }

                    return $this->redirect(
                        $this->generateUrl('subscription_manager').'#subscriptions'
                    );
                }

                if(null !== $request->get('save_and_stay', null)){
                    return $this->redirectToRoute('subscription_subscription_edit', [
                        'id' => $subscription->getId()
                    ]);
                }
            }

            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $subscriptionManager->getErrors())
                ]));
        }


        return $this->render('subscription/subscription/subscriptionEdit.html.twig', array(
            'formSubscription' => $formHandler->getForm()->createView()
        ));
    }
}
