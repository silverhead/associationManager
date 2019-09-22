<?php

namespace AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Solde
 * @package AccountingBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AccountingBundle\Repository\SoldeRepository")
 */
class Solde {
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date;
    
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $amount;
    
    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isPrev;
    
    /**
     * @var AccountableAccount
     * @ORM\ManyToOne(targetEntity="AccountingBundle\Entity\AccountableAccount", inversedBy="soldes", fetch="EAGER")
     * ORM\JoinColumn(name="accountable_account_id", referencedColumnName="id")
     */
    protected $accountableAccount;
    
    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $updatedAt;
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Solde
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $valueDate
     * @return Solde
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Solde
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }
    /**
     * @return string
     */
    public function getFormattedAmount()
    {
        return number_format($this->amount/100, 2, ',', ' ');
    }
    
    /**
     * @param int $amountPrev
     * @return Solde
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }
    
    /**
     * @return bool
     */
    public function getIsPrev()
    {
        return $this->isPrev;
    }

    /**
     * @param bool $isPrev
     * @return Solde
     */
    public function setIsPrev($isPrev)
    {
        $this->isPrev = $isPrev;

        return $this;
    }
    
    /**
     * @return \AccountingBundle\Entity\AccountableAccount|null
     */
    public function getAccountableAccount():?AccountableAccount {
        return $this->accountableAccount;
    }
    
    /**
     * 
     * @param \AccountingBundle\Entity\AccountableAccount $accountableAccount
     * @return \AccountingBundle\Entity\Solde
     */
    public function setAccountableAccount(AccountableAccount $accountableAccount): Solde {
        $this->accountableAccount = $accountableAccount;
        
        return $this;
    }
}
