<?php

namespace SubscriptionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PaymentTypeController extends Controller
{
    const ITEMS_PER_PAGE = 4;
    const PAGE_PARAMETER_NAME = 'pageTab3';

    /**
     * @Route("/subscription/payment/list-part/{anchor}", name="subscription_payment_type_list_part",  options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $anchor = null)
    {
        $page = $request->get(self::PAGE_PARAMETER_NAME, 1);
        $currentRoute = $request->get('masterRoute', $request->get('_route'));

        $paymentTypeManager = $this->get('subscription.manager.paymentType');

        $results = $paymentTypeManager->paginatedList(
            $page,
            self::ITEMS_PER_PAGE,
            self::PAGE_PARAMETER_NAME,
            $anchor,
            $currentRoute
        );

        return $this->render(
            '/subscription/paymentType/list.html.twig',
            array(
                'results' => $results,
            )
        );
    }

    /**
     * @Route("/subscription/paymentType/json/save", name="subscription_payment_type_save_json", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');

        $this->denyAccessUnlessGranted('SUBSCRIPTION_PAYMENT_TYPE_EDIT', null, $translator->trans('app.common.access_denied'));

        $id = $request->get('id', null);
        $label = $request->get('label', null);

        if (null === $id && null === $label) {
            throw new HttpException(
                "Arguments of the request are missing, you must send the \"id\" and \"label\" arguments for the call of this method!"
            );
        }

        $manager = $this->get('subscription.manager.paymentType');

        $entity = $manager->find($id);

        if(null === $entity){
            $entity = $manager->getNewEntity();
        }

        try {
            $entity->setLabel($label);
            $manager->save($entity);

            $array = [
                'code' => 'success',
                'message' => $translator->trans('subscription.paymentType.edit.saveSuccessText'),
            ];

        } catch (\Exception $e) {
            $array = [
                'code' => 'error',
                'message' => $translator->trans(
                    'app.common.errorComming',
                    [
                        '%error%' => $e->getCode()." : ".$e->getMessage(),
                    ]
                ),
            ];
        }

        return new Response(
            (new Serializer(
                [new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json')
        );
    }


    /**
     * @Route("/subscription/paymentType/delete/{id}", name="subscription_payment_type_delete", options={"expose"=true})
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');
        $manager = $this->get('subscription.manager.paymentType');

        $entity = $manager->find($id);

        if (null === $entity) {
            $array = [
                'code' => 'error',
                'message' => $translator->trans('subscription.paymentType.delete.deleteErrorMissingText'),
            ];

            return new Response(
                (new Serializer(
                    [new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json')
            );
        }

        if (!$manager->delete($entity)) {
            $array = [
                'code' => 'error',
                'message' => $translator->trans(
                    'app.common.errorComming',
                    [
                        '%error%' => '<br />'.implode('<br />', $manager->getErrors()),
                    ]
                ),
            ];

            return new Response(
                (new Serializer(
                    [new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json')
            );
        }

        $array = [
            'code' => 'success',
            'message' => $translator->trans(
                'subscription.paymentType.delete.deleteSuccessText',
                ['%label%' => $entity->getLabel()]
            ),
        ];

        return new Response(
            (new Serializer(
                [new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json')
        );
    }
}