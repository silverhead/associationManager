<?php

namespace SubscriptionBundle\Entity;

use MemberBundle\Validator\Entity\IsUniqueEmailEntity;
use SubscriptionBundle\Validator\constraint as SubscriptionAssert;
use Symfony\Component\Validator\Constraints as Assert;
use MemberBundle\Validator\Constraint as MemberAssert;

class SubscriptionFeeImport implements IsUniqueEmailEntity
{
    const MIN_COLUMN_LENGTH = 6;

    const STATE_IGNORED = 0;
    const STATE_CREATED = 1;
    const STATE_UPDATED = 2;

    /**
     * @SubscriptionAssert\SubscriptionExistConstraint(message="subscription.fee.import.error.subscription_not_found")
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @var string
     */
    private $subscriptionCode;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @var string
     */
    private $firstName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @var string
     */
    private $lastName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Assert\Email()
     * @MemberAssert\UniqueEmailConstraint(message="member.import.unique_email")
     * @var string
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @var float
     */
    private $amount;

    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     * @var string
     */
    private $startDate;

    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     * @var string
     */
    private $endDate;

    /**
     * @SubscriptionAssert\PaymentTypeExistConstraint(message="subscription.fee.import.error.payment_not_found")
     * @var string
     */
    private $paymentCode;

    /**
     * @Assert\Date()
     * @var string
     */
    private $paymentDate;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var int
     */
    private $numLine;


    /**
     * @var int
     */
    private $state;
    /**
     * @var array
     */
    private $numColumnByProperty = array(
        'lastName' => 1,
        'firstName' => 2,
        'email' => 3,
        'subscriptionCode' => 4,
        'amount' => 5,
        'startDate' => 6,
        'endDate' => 7,
        'paymentCode' => 8,
        'paymentDate' => 9,
        'comment' => 10
    );

    public function __construct()
    {
        $this->state = self::STATE_IGNORED;

        $this->comment = "";
    }


    /**
     * @return string
     */
    public function getSubscriptionCode(): ?string
    {
        return $this->subscriptionCode;
    }

    /**
     * @param string $subscriptionCode
     * @return SubscriptionFeeImport
     */
    public function setSubscriptionCode(string $subscriptionCode): SubscriptionFeeImport
    {
        $this->subscriptionCode = $subscriptionCode;

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
     * @return SubscriptionFeeImport
     */
    public function setFirstName(string $firstName): SubscriptionFeeImport
    {
        $this->firstName = $firstName;

        return $this;
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
     * @return SubscriptionFeeImport
     */
    public function setLastName(string $lastName): SubscriptionFeeImport
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return SubscriptionFeeImport
     */
    public function setEmail(string $email): SubscriptionFeeImport
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return SubscriptionFeeImport
     */
    public function setAmount(float $amount): SubscriptionFeeImport
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     * @param string $startDate
     * @return SubscriptionFeeImport
     */
    public function setStartDate(string $startDate): SubscriptionFeeImport
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    /**
     * @param string $endDate
     * @return SubscriptionFeeImport
     */
    public function setEndDate(string $endDate): SubscriptionFeeImport
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentCode(): ?string
    {
        return $this->paymentCode;
    }

    /**
     * @param string $paymentCode
     * @return SubscriptionFeeImport
     */
    public function setPaymentCode(string $paymentCode): SubscriptionFeeImport
    {
        $this->paymentCode = $paymentCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentDate(): ?string
    {
        return $this->paymentDate;
    }

    /**
     * @param string $paymentDate
     * @return SubscriptionFeeImport
     */
    public function setPaymentDate(string $paymentDate): SubscriptionFeeImport
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return SubscriptionFeeImport
     */
    public function setComment(string $comment): SubscriptionFeeImport
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumLine(): int
    {
        return $this->numLine;
    }

    /**
     * @param int $numLine
     * @return SubscriptionFeeImport
     */
    public function setNumLine(int $numLine): SubscriptionFeeImport
    {
        $this->numLine = $numLine;

        return $this;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param int $state
     * @return SubscriptionFeeImport
     */
    public function setState(int $state): SubscriptionFeeImport
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumColumnByProperty(string $propertyName): int
    {
        if (!isset($this->numColumnByProperty[$propertyName])){
            throw new \Exception('Not number found for the propertyName "'.$propertyName.'"');
        }

        return $this->numColumnByProperty[$propertyName];
    }

    /**
     * @param array $numColumnByProperty
     * @return SubscriptionFeeImport
     */
    public function setNumColumnByProperty(array $numColumnByProperty): SubscriptionFeeImport
    {
        $this->numColumnByProperty = $numColumnByProperty;

        return $this;
    }
}