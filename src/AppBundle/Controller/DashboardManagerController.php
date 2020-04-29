<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DashboardBundleSetting;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DashboardManagerController extends Controller
{
    /**
     * @Route("mamager/dashboard", name="dashboard_manager")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dashboardManagerAction()
    {
        $translator = $this->get('translator');

        if(!$this->isGranted("APP_SETTING_VIEW")){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));

            return $this->redirect(
                $this->generateUrl('dashboard')
            );
        }

        return $this->render(":dashboard:dashboardManager.html.twig", array(
            'menuSelect' => 'dashboard_manager',
            'bundles' => $this->list()
        ));
    }

    /**
     * @Route("manager/dashboard/list_part", name="dashboard_list_part",  options = { "expose" = true })
     *
     */
    public function listAction()
    {
        return $this->render(":dashboard:dashboardList.html.twig", array(
            'bundles' => $this->list()
        ));
    }

    private function list()
    {
        $dashboardBundleManger = $this->get('app.manager.dashboard_bundle_setting');
        return $dashboardBundleManger->getList(0, 0);
    }

    /**
     * @Route("mamager/dashboard/edit/{id}", name="dashboard_edit", defaults={"id": 0},  options = { "expose" = true })
     * @param Request $request
     */
    public function editAction(Request $request, $id = 0)
    {
        $translator = $this->get('translator');

        if(!$this->isGranted("APP_SETTING_VIEW")){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));

            return $this->redirect(
                $this->generateUrl('dashboard')
            );
        }

        $dashboardBundleManger = $this->get('app.manager.dashboard_bundle_setting');

        if($id > 0){
            $dashboardBundleSetting =  $dashboardBundleManger->find($id);
        }
        else{
            $dashboardBundleSetting = new DashboardBundleSetting();
        }

        $formHandler = $this->get('app.form.handler.dashboard_bundle_setting');
        $formHandler->setForm($dashboardBundleSetting);

        $form = $formHandler->getForm();

        if($formHandler->process($request)){
            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('dashboard.manager.form.saveSuccessText'));

            $bundles =  $form->getData();

            $dashboardBundleManger->saveBundles($bundles);
        }

        return $this->render(":dashboard:dashboardSettingForm.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("manager/dashboard/save/{id}", name="dashboard_save", defaults={"id": 0} , options = { "expose" = true })
     */
    public function saveAction(Request $request, $id = 0)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');

        $this->denyAccessUnlessGranted('APP_SETTING_VIEW', null, $translator->trans('app.common.access_denied'));

        $dashboardBundleManger = $this->get('app.manager.dashboard_bundle_setting');

        if($id > 0){
            $dashboardBundleSetting =  $dashboardBundleManger->find($id);
        }
        else{
            $dashboardBundleSetting = new DashboardBundleSetting();
        }

        $formHandler = $this->get('app.form.handler.dashboard_bundle_setting');
        $formHandler->setForm($dashboardBundleSetting);

        $jsonMessage = [];

        if($formHandler->process($request)){
            $dashboardBundleSetting =  $formHandler->getData();

            if($dashboardBundleManger->save($dashboardBundleSetting)){
                $jsonMessage = [
                    'code' => 'success',
                    'message' => $translator->trans('dashboard.form.saveSuccessText', ['%bundleName%' => $translator->trans($dashboardBundleSetting->getBundleCode()) ]),
                ];
            }
            else{
                $jsonMessage = [
                    'code' => 'error',
                    'message' => $translator->trans(
                        'app.common.errorComming',
                        [
                            '%error%' => '<br />' . implode('<br />', $dashboardBundleManger->getErrors())
                        ]
                    ),
                ];
            }
        }

        return new Response(
            (new Serializer(
                [new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($jsonMessage, 'json')
        );
    }

    /**
     * @Route("manager/dashboard/delete/{id}", name="dashboard_delete", options = { "expose" = true })
     */
    public function deleteAction(Request $request, $id)
    {
        if(!$request->isXmlHttpRequest()){
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');

        $this->denyAccessUnlessGranted('APP_SETTING_VIEW', null, $translator->trans('app.common.access_denied'));

        $dashboardBundleManger = $this->get('app.manager.dashboard_bundle_setting');
        $dashboardBundleSetting =  $dashboardBundleManger->find($id);


        if(null === $dashboardBundleSetting){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('dashboard.delete.deleteErrorMissingText')
            ];

            return new Response(
                (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json'));
        }

        if(!$dashboardBundleManger->delete($dashboardBundleSetting)){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $dashboardBundleManger->getErrors())
                ])
            ];

            return new Response(
                (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json'));
        }

        $array = [
            'code' => 'success',
            'message' => $translator->trans('dashboard.delete.deleteSucessText')
        ];

        return new Response(
            (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json')
        );
    }
}
