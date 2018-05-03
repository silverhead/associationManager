<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DashboardBundleSetting;
use AppBundle\Event\DashboardBundleEvent;
use AppBundle\Form\Model\DashboardBundleCollectionModel;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DashboardManagerController extends Controller
{
    /**
     * @Route("mamager/dashboard", name="dashboard_manager")
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dashboardManagerAction()
    {
        $dashboardBundleManger = $this->get('app.manager.dashboard_bundle_setting');

        $dashboardBundles = $dashboardBundleManger->getList(0, 0);

        $dashboardBundleCollection = new DashboardBundleCollectionModel();

        foreach ($dashboardBundles as $dashboardBundle){
            $dashboardBundleCollection->addBundle($dashboardBundle);
        }

        $formHandler = $this->get('app.form.handler.dashboard_bundle_setting');
        $formHandler->setForm($dashboardBundleCollection);
        $form = $formHandler->getForm();

        return $this->render(":dashboard:dashboardManager.html.twig", array(
            'form' => $form->createView()
//            'bundles' => $bundles,
//            'groups' => $groups
        ));
    }
}