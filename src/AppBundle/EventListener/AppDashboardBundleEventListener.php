<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\DashboardBundle;
use AppBundle\Event\DashboardBundleEvent;

class AppDashboardBundleEventListener
{
    /**
     * @var DashboardBundle
     */
    private $dashboardBundle;

    private $code;

    public function __construct($code, $data)
    {
        $this->code = $code;

        $dashboardBundle = new DashboardBundle();
        $dashboardBundle->setBundleCode($code);
        $dashboardBundle->setService($data['service']);
        $dashboardBundle->setAction($data['action']);
        $dashboardBundle->setLabel($data['label']);
        $dashboardBundle->setJsFiles($data['js_files']);

        $this->dashboardBundle = $dashboardBundle;
    }

    public function onProvideBundle(DashboardBundleEvent $event)
    {
        $event->addBundle($this->code, $this->dashboardBundle);
    }
}