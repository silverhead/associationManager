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
    protected $amountReal;
    
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $amountPrev;
    
    /**
     * @var AccountableAccount
     * @ORM\ManyToOne(targetEntity="AccountingBundle\Entity\AccountableAccount", inversedBy="entries", fetch="EAGER")
     * ORM\JoinColumn(name="accountable_account_id", referencedColumnName="id")
     */
    protected $accountableAccount;
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Entry
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getValueDate()
    {
        return $this->valueDate;
    }

    /**
     * @param \DateTime $valueDate
     * @return Entry
     */
    public function setValueDate($valueDate)
    {
        $this->valueDate = $valueDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmountPrev()
    {
        return $this->amountPrev;
    }

    /**
     * @param int $amountPrev
     * @return Solde
     */
    public function setAmountPrev($amountPrev)
    {
        $this->amountPrev = $amountPrev;

        return $this;
    }
    
    /**
     * @return int
     */
    public function getAmountReal()
    {
        return $this->amountReal;
    }

    /**
     * @param int $amountReal
     * @return Solde
     */
    public function setAmountReal($amountReal)
    {
        $this->amountReal = $amountReal;

        return $this;
    }
    
    /**
     * @return \AccountingBundle\Entity\AccountableAccount|null
     */
    public function getAccountableAccount():?AccountableAccount {
        return $this->accountableAccount;
    }
}
