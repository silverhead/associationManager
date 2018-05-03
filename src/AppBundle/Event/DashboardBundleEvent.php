<?php

namespace AppBundle\Event;


use AppBundle\Entity\DashboardBundle;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\Event;

class DashboardBundleEvent extends Event
{
    const EVENT_NAME = 'app.dashboard.bundle.event';

    private $bundles = array();

    /**
     * @param string $bundleCode
     * @param DashboardBundle $dashboardBundle
     */
    public function addBundle(string $bundleCode, DashboardBundle $dashboardBundle): void
    {
        if(isset($this->bundles[$bundleCode])){
            throw new Exception("Bundle with a same key already exist!");
        }

        $this->bundles[$bundleCode] = $dashboardBundle;
    }

    /**
     * @return array
     */
    public function getBundlesList(): array
    {
        return $this->bundles;
    }

    /**
     * @param $bundleName
     * @return DashboardBundle|null
     */
    public function getBundleByKey($bundleName): ?DashboardBundle
    {
        return isset($this->bundles[$bundleName])?$this->bundles[$bundleName]:null;
    }
}