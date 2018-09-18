<?php

namespace MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SubscriptionBundle\Entity\Subscription;
use SubscriptionBundle\Entity\SubscriptionPaymentPeriodicity;

/**
 * Class MemberSubscriptionHistorical
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\MemberSubscriptionHistoricalRepository")
 */
class MemberSubscriptionHistorical
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Member
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\Member", inversedBy="subscriptions")
     */
    private $member;

    /**
     * @var Subscription
     * @ORM\ManyToOne(targetEntity="SubscriptionBundle\Entity\Subscription", inversedBy="memberSubscription")
     */
    private $subscription;

    /**
     * @var SubscriptionPaymentPeriodicity
     * @ORM\ManyToOne(targetEntity="SubscriptionBundle\Entity\SubscriptionPaymentPeriodicity")
     */
    private $subscriptionPaymentPeriodicity;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $endDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return MemberSubscriptionHistorical
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return MemberSubscriptionHistorical
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set cost
     *
     * @param float $cost
     *
     * @return MemberSubscriptionHistorical
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set member
     *
     * @param \MemberBundle\Entity\Member $member
     *
     * @return MemberSubscriptionHistorical
     */
    public function setMember(\MemberBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \MemberBundle\Entity\Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set subscription
     *
     * @param \AppBundle\Entity\Subscription $subscription
     *
     * @return MemberSubscriptionHistorical
     */
    public function setSubscription(\SubscriptionBundle\Entity\Subscription $subscription = null)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return \SubscriptionBundle\Entity\Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Set subscriptionPaymentPeriodicity
     *
     * @param \SubscriptionBundle\Entity\SubscriptionPaymentPeriodicity $subscriptionPaymentPeriodicity
     *
     * @return MemberSubscriptionHistorical
     */
    public function setSubscriptionPaymentPeriodicity(\SubscriptionBundle\Entity\SubscriptionPaymentPeriodicity $subscriptionPaymentPeriodicity = null)
    {
        $this->subscriptionPaymentPeriodicity = $subscriptionPaymentPeriodicity;

        return $this;
    }

    /**
     * Get subscriptionPaymentPeriodicity
     *
     * @return \SubscriptionBundle\Entity\SubscriptionPaymentPeriodicity
     */
    public function getSubscriptionPaymentPeriodicity()
    {
        return $this->subscriptionPaymentPeriodicity;
    }
}
