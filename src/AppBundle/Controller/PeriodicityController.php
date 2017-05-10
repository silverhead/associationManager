<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PeriodicityController extends Controller
{
    const ITEMS_PER_PAGE = 4;
    const PAGE_PARAMETER_NAME = 'pageTab2';
    /**
     * @Route("/subscription/periodicity_edit/{id}", name="subscription_periodicity_edit")
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

        if(null === $periodicity){
            $periodicity = $periodicityManager->getNewEntity();
        }

        $formHandler->setForm($periodicity);

        if($formHandler->process($request)){
            $translator = $this->get('translator');

            $periodicity = $formHandler->getData();

            if($periodicityManager->save($periodicity)){

                $this->addFlash('success', $translator->trans('app.subscription.periodicity.edit.saveSucessText'));

                if(null !== $request->get('save_and_leave', null)){
                    $pageH = $this->get('app.handler.page_historical');
                    $callBackUrl = $pageH->getCallbackUrl('subscription_periodicity_edit');

                    if(null!== $callBackUrl){
                        return $this->redirect($callBackUrl);
                    }

                    return $this->redirect(
                        $this->generateUrl('subscription_manager').'#periodicities'
                    );
                }

                if(null !== $request->get('save_and_stay', null)){
                    return $this->redirectToRoute('subscription_periodicity_edit', [
                        'id' => $periodicity->getId()
                    ]);
                }
            }

            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $periodicityManager->getErrors())
                ]));
        }

        return $this->render('/subscription/periodicity/periodicityEdit.html.twig', array(
            'formPeriodicity' => $formHandler->getForm()->createView()
        ));
    }

    /**
     * @Route("/subscription//periodicity_delete/{id}",
     *     name="subscription_periodicity_delete", options = { "expose" = true }, methods={"POST"})
     * @param $id
     */
    public function deleteAction(Request $request, $id)
    {
        if(!$request->isXmlHttpRequest()){
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');
        $periodicityManager = $this->get('app.subscription.manager.periodicity');

        $periodicity = $periodicityManager->find($id);

        if(null === $periodicity){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('app.subscription.periodicity.deleteErrorMissingText')
            ];

            return new Response(
                (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json'));
        }

        if(!$periodicityManager->delete($periodicity)){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $periodicityManager->getErrors())
                ])
            ];

            return new Response(
                (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json'));
        }

        $array = [
            'code' => 'success',
            'message' => $translator->trans('app.subscription.periodicity.edit.saveSucessText')
        ];

        return new Response(
            (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
        ))->serialize($array, 'json'));

    }

    /**
     * @Route("/subscription/periodicities/list-part", name="subscription_periodicity_list_part",  options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $anchor = null)
    {
        $page =  $request->get(self::PAGE_PARAMETER_NAME, 1);
        $currentRoute = $request->get('_route');

        $periodicityManager = $this->get('app.subscription.manager.periodicity');

        $results = $periodicityManager->paginatedList($page, self::ITEMS_PER_PAGE, self::PAGE_PARAMETER_NAME);

        $pageH = $this->get('app.handler.page_historical');

        $pageH->setCallbackUrl('subscription_periodicity_edit',
            $this->generateUrl($currentRoute),
            [self::PAGE_PARAMETER_NAME => $page],
            $anchor
        );

        return $this->render('/subscription/periodicity/periodicityList.html.twig', array(
            'results' => $results
        ));
    }
}
