<?php

namespace SubscriptionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MemberBundle\Entity\MemberSubscriptionHistorical;


/**
 * Class Subscription
 * @package SubscriptionBundle\Entity
 * @ORM\Entity(repositoryClass="SubscriptionBundle\Repository\SubscriptionRepository")
 */
class Subscription
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="SubscriptionPaymentPeriodicity", inversedBy="subscriptions")
     */
    private $periodicities;

    /**
     * @var MemberSubscriptionHistorical
     * @ORM\OneToMany(targetEntity="MemberBundle\Entity\MemberSubscriptionHistorical", mappedBy="subscription")
     */
    private $memberSubscription;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $active;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->periodicities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Subscription
     */
    public function setId($id): Subscription
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Subscription
     */
    public function setLabel(string $label): Subscription
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     * @return Subscription
     */
    public function setCost(float $cost): Subscription
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return Subscription
     */
    public function setDuration(int $duration): Subscription
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Subscription
     */
    public function setActive(bool $active): Subscription
    {
        $this->active = $active;

        return $this;
    }




    /**
     * Add periodicity
     *
     * @param SubscriptionPaymentPeriodicity $periodicity
     *
     * @return Subscription
     */
    public function addPeriodicity(SubscriptionPaymentPeriodicity $periodicity)
    {
        $this->periodicities[] = $periodicity;

        return $this;
    }

    /**
     * Remove periodicity
     *
     * @param SubscriptionPaymentPeriodicity $periodicity
     */
    public function removePeriodicity(SubscriptionPaymentPeriodicity $periodicity)
    {
        $this->periodicities->removeElement($periodicity);
    }

    /**
     * @return SubscriptionPaymentPeriodicity
     */
    public function getPeriodicities()
    {
        return $this->periodicities;
    }


    /**
     * Add memberSubscription
     *
     * @param \MemberBundle\Entity\MemberSubscriptionHistorical $memberSubscription
     *
     * @return Subscription
     */
    public function addMemberSubscription(\MemberBundle\Entity\MemberSubscriptionHistorical $memberSubscription)
    {
        $this->memberSubscription[] = $memberSubscription;

        return $this;
    }

    /**
     * Remove memberSubscription
     *
     * @param \MemberBundle\Entity\MemberSubscriptionHistorical $memberSubscription
     */
    public function removeMemberSubscription(\MemberBundle\Entity\MemberSubscriptionHistorical $memberSubscription)
    {
        $this->memberSubscription->removeElement($memberSubscription);
    }

    /**
     * Get memberSubscription
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMemberSubscription()
    {
        return $this->memberSubscription;
    }

    public function canDeleted()
    {
        return $this->getMemberSubscription()->count()==0?true:false;
    }
}
