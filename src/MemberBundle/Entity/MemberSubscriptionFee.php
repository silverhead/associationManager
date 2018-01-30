<?php

namespace MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SubscriptionBundle\Entity\SubscriptionPaymentType;

/**
 * Class MemberSubscriptionFee
 * @package MemberBundle\Entity
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\MemberSubscriptionFeeRepository")
 */
class MemberSubscriptionFee
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var Member
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\Member", inversedBy="fees")
     */
    private $member;

    /**
     * @var MemberSubscriptionHistorical
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\MemberSubscriptionHistorical")
     */
    private $subscription;

    /**
     * @var SubscriptionPaymentType
     * @ORM\ManyToOne(targetEntity="SubscriptionBundle\Entity\SubscriptionPaymentType")
     */
    private $payment;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private $paymentDate;

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
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $paid;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MemberSubscriptionFee
     */
    public function setId(int $id): MemberSubscriptionFee
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Member
     */
    public function getMember(): Member
    {
        return $this->member;
    }

    /**
     * @param Member $member
     * @return MemberSubscriptionFee
     */
    public function setMember(Member $member): MemberSubscriptionFee
    {
        $this->member = $member;

        return $this;
    }

    /**
     * @return MemberSubscriptionHistorical
     */
    public function getSubscription(): MemberSubscriptionHistorical
    {
        return $this->subscription;
    }

    /**
     * @param MemberSubscriptionHistorical $subscription
     * @return MemberSubscriptionFee
     */
    public function setSubscription(MemberSubscriptionHistorical $subscription): MemberSubscriptionFee
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     * @return MemberSubscriptionFee
     */
    public function setCost(float $cost): MemberSubscriptionFee
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     * @return MemberSubscriptionFee
     */
    public function setStartDate(\DateTime $startDate): MemberSubscriptionFee
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     * @return MemberSubscriptionFee
     */
    public function setEndDate(\DateTime $endDate): MemberSubscriptionFee
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Set paid.
     *
     * @param bool $paid
     *
     * @return MemberSubscriptionFee
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid.
     *
     * @return bool
     */
    public function getPaid(): bool
    {
        return $this->paid;
    }

    /**
     * Set payment.
     *
     * @param \SubscriptionBundle\Entity\SubscriptionPaymentType|null $payment
     *
     * @return MemberSubscriptionFee
     */
    public function setPayment(\SubscriptionBundle\Entity\SubscriptionPaymentType $payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment.
     *
     * @return \SubscriptionBundle\Entity\SubscriptionPaymentType
     */
    public function getPayment(): SubscriptionPaymentType
    {
        return $this->payment;
    }

    /**
     * @return \DateTime
     */
    public function getPaymentDate():? \DateTime
    {
        return $this->paymentDate;
    }

    /**
     * @param \DateTime $paymentDate
     * @return MemberSubscriptionFee
     */
    public function setPaymentDate(\DateTime $paymentDate): MemberSubscriptionFee
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }


}
