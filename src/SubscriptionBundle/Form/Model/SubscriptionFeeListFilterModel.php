<?php

namespace SubscriptionBundle\Form\Model;


use SubscriptionBundle\Entity\Subscription;

class SubscriptionFeeListFilterModel
{
    /**
     * @var Subscription
     */
    private $subscription;

    /**
     * @var string
     */
    private $fullNameMember;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var bool
     */
    private $paid;

    /**
     * @return Subscription
     */
    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    /**
     * @param Subscription $subscription
     * @return SubscriptionFeeListFilterModel
     */
    public function setSubscription(Subscription $subscription = null): SubscriptionFeeListFilterModel
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullNameMember(): ?string
    {
        return $this->fullNameMember;
    }

    /**
     * @param string $fullNameMember
     * @return SubscriptionFeeListFilterModel
     */
    public function setFullNameMember(string $fullNameMember = null): SubscriptionFeeListFilterModel
    {
        $this->fullNameMember = $fullNameMember;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     * @return SubscriptionFeeListFilterModel
     */
    public function setStartDate(\DateTime $startDate = null): SubscriptionFeeListFilterModel
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     * @return SubscriptionFeeListFilterModel
     */
    public function setEndDate(\DateTime $endDate = null): SubscriptionFeeListFilterModel
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPaid(): ?bool
    {
        return $this->paid;
    }

    /**
     * @param bool $paid
     * @return SubscriptionFeeListFilterModel
     */
    public function setPaid(bool $paid = null): SubscriptionFeeListFilterModel
    {
        $this->paid = $paid;

        return $this;
    }
}