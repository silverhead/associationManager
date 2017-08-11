<?php

namespace SubscriptionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Periodicity
 * @package SubscriptionBundle\Entity
 * @ORM\Entity(repositoryClass="SubscriptionBundle\Repository\PeriodicityRepository")
 */
class SubscriptionPaymentPeriodicity
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var integer
     * @ORM\Column(type="integer", length=3, nullable=false)
     */
    private $duration;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $active;


    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="SubscriptionBundle\Entity\Subscription", mappedBy="periodicities")
     */
    private $subscriptions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return SubscriptionPaymentPeriodicity
     */
    public function setId(int $id): SubscriptionPaymentPeriodicity
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
     * @return SubscriptionPaymentPeriodicity
     */
    public function setLabel(string $label): SubscriptionPaymentPeriodicity
    {
        $this->label = $label;

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
     * @return SubscriptionPaymentPeriodicity
     */
    public function setDuration(int $duration): SubscriptionPaymentPeriodicity
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
     * @return SubscriptionPaymentPeriodicity
     */
    public function setActive(bool $active): SubscriptionPaymentPeriodicity
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add subscription
     *
     * @param Subscription $subscription
     *
     * @return SubscriptionPaymentPeriodicity
     */
    public function addSubscription(Subscription $subscription)
    {
        $this->subscriptions[] = $subscription;

        return $this;
    }

    /**
     * Remove subscription
     *
     * @param Subscription $subscription
     */
    public function removeSubscription(Subscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    public function canDeleted()
    {
        return $this->subscriptions->count()==0?true:false;
    }
}
