<?php

namespace AppBundle\Controller;

use AppBundle\Event\DashboardBundleEvent;
use AppBundle\QueryHelper\FilterQuery;
use AppBundle\QueryHelper\OrderQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
        return $this->render('main/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        $dashboardBundleEvent = new DashboardBundleEvent();
        $this->container->get('event_dispatcher')->dispatch(
            DashboardBundleEvent::EVENT_NAME,
            $dashboardBundleEvent
        );
        $bundles = $dashboardBundleEvent->getBundlesList();

        $dashboardBundlesManager  = $this->get('app.manager.dashboard_bundle_setting');

        $dashboardBundlesList = $dashboardBundlesManager->getListByUserGroup($this->getUser()->getGroup());

        $dashboardBundlesActionList = array();
        $jsBundles = array();

        foreach ($dashboardBundlesList as $bundleSetting){
            if($bundles[$bundleSetting->getBundleCode()]){
                $bundle =  $bundles[$bundleSetting->getBundleCode()];
                $dashboardBundlesActionList[] = $this->get($bundle->getService())->getAction($bundle->getAction());

                foreach($bundle->getJsFiles() as $jsFile){
                    $jsBundles[] = $jsFile;
                }
            }
        }

        return $this->render('main/dashboard.html.twig', array(
            'dashboardBundlesList' => $dashboardBundlesActionList,
            'jsBundles' => $jsBundles
        ));
    }
}
