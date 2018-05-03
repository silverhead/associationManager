<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\UserGroup;

/**
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DashboardBundleSettingRepository")
 */
class DashboardBundleSetting
{

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     *
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $bundleCode;

    /**
     * @var DashboardBundle
     */
    private $bundle;

    /**
     * @var UserGroup
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\UserGroup")
     */
    private $group;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $order;

    public function __construct()
    {
        $this->order = 0;
    }

    /**
     * @return int
     */
    public function getId():? int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return DashboardBundleSetting
     */
    public function setId(int $id): DashboardBundleSetting
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getBundleCode():? string
    {
        return $this->bundleCode;
    }

    /**
     * @param string $bundleCode
     * @return DashboardBundleSetting
     */
    public function setBundleCode(string $bundleCode): DashboardBundleSetting
    {
        $this->bundleCode = $bundleCode;

        return $this;
    }

    /**
     * @return UserGroup
     */
    public function getGroup():? UserGroup
    {
        return $this->group;
    }

    /**
     * @param UserGroup $group
     * @return DashboardBundleSetting
     */
    public function setGroup(UserGroup $group): DashboardBundleSetting
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder():? int
    {
        return $this->order;
    }

    /**
     * @param int $order
     * @return DashboardBundleSetting
     */
    public function setOrder(int $order): DashboardBundleSetting
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return DashboardBundle
     */
    public function getBundle():? DashboardBundle
    {
        return $this->bundle;
    }

    /**
     * @param DashboardBundle $bundle
     * @return DashboardBundleSetting
     */
    public function setBundle(DashboardBundle $bundle): DashboardBundleSetting
    {
        $this->bundle = $bundle;

        return $this;
    }
}
