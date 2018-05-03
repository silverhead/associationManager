<?php

namespace AppBundle\Form\Model;

use AppBundle\Entity\DashboardBundleSetting;
use Doctrine\Common\Collections\ArrayCollection;

class DashboardBundleCollectionModel
{
    /**
     * @var ArrayCollection
     */
    private $bundles;


    public function __construct()
    {
        $this->bundles = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getBundles():? ArrayCollection
    {
        return $this->bundles;
    }

    /**
     * @param ArrayCollection $bundles
     * @return DashboardBundleCollectionModel
     */
    public function setBundles(ArrayCollection $bundles): DashboardBundleCollectionModel
    {
        $this->bundles = $bundles;

        return $this;
    }

    public function addBundle(DashboardBundleSetting $dashboardBundle)
    {
        $this->bundles->add($dashboardBundle);
    }

    public function removeBundle(DashboardBundleSetting $dashboardBundle)
    {
        $this->bundles->removeElement($dashboardBundle);
    }
}