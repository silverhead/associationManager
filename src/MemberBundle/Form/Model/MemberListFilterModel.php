<?php

namespace MemberBundle\Form\Model;


use MemberBundle\Entity\MemberStatus;
use SubscriptionBundle\Entity\Subscription;

class MemberListFilterModel
{
    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var MemberStatus
     */
    private $status;

    /**
     * @var Subscription
     */
    private $subscription;

    /**
     * @var bool
     */
    private $onlyNewFeeComingSoon;

    /**
     * @var bool
     */
    private $onlyLatePaymentMember;

    /**
     * @var bool
     */
    private $active;

    public function __construct()
    {
        $this->lastName = "";
        $this->firstName = "";
        $this->subscription = new Subscription();
        $this->status = new MemberStatus();
        $this->active = true;
        $this->onlyNewFeeComingSoon = false;
        $this->onlyLatePaymentMember = false;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return MemberListFilterModel
     */
    public function setLastName(?string $lastName): MemberListFilterModel
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return MemberListFilterModel
     */
    public function setFirstName(?string $firstName): MemberListFilterModel
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return MemberStatus
     */
    public function getStatus(): ?MemberStatus
    {
        return $this->status;
    }

    /**
     * @param MemberStatus $status
     * @return MemberListFilterModel
     */
    public function setStatus(?MemberStatus $status): MemberListFilterModel
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Subscription
     */
    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    /**
     * @param Subscription $subscription
     * @return MemberListFilterModel
     */
    public function setSubscription(?Subscription $subscription): MemberListFilterModel
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return MemberListFilterModel
     */
    public function setActive(?bool $active): MemberListFilterModel
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOnlyNewFeeComingSoon(): bool
    {
        return $this->onlyNewFeeComingSoon;
    }

    /**
     * @param bool $onlyNewFeeComingSoon
     * @return MemberListFilterModel
     */
    public function setOnlyNewFeeComingSoon(bool $onlyNewFeeComingSoon): MemberListFilterModel
    {
        $this->onlyNewFeeComingSoon = $onlyNewFeeComingSoon;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOnlyLatePaymentMember(): bool
    {
        return $this->onlyLatePaymentMember;
    }

    /**
     * @param bool $onlyLatePaymentMember
     * @return MemberListFilterModel
     */
    public function setOnlyLatePaymentMember(bool $onlyLatePaymentMember): MemberListFilterModel
    {
        $this->onlyLatePaymentMember = $onlyLatePaymentMember;

        return $this;
    }
}