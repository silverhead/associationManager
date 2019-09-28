<?php

namespace AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Entry
 * @package AccountingBundle\Entity
 * 
 * @ORM\Entity(repositoryClass="AccountingBundle\Repository\EntryRepository")
 */
class Entry
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    protected $reference;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $accountingDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $valueDate;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
        protected $amount;

    /**
     * @var Entry
     * @ORM\OneToOne(targetEntity="AccountingBundle\Entity\Entry", fetch="EAGER")
     */
    protected $entryParent;
    
    /**
     * var int
     * ORM\Column(type="integer", nullable=true)
     */
    //protected $projectId;

    /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="AccountingBundle\Entity\Project", inversedBy="entries", fetch="EAGER")
     */
    protected $project;

    /**
     * @var AccountableAccount
     * @ORM\ManyToOne(targetEntity="AccountingBundle\Entity\AccountableAccount", inversedBy="entries", fetch="EAGER")
     */
    protected $accountableAccount;
    
    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $active;
    
    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $updatedAt;

    public function __construct() { }

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
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return Entry
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAccountingDate()//: \DateTime
    {
        return $this->accountingDate;
    }

    /**
     * @param \DateTime $accountingDate
     * @return Entry
     */
    public function setAccountingDate(\DateTime $accountingDate = null): Entry
    {
        $this->accountingDate = $accountingDate;

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
    public function getAmount()
    {
        return $this->amount;
    }
    /**
     * @return string
     */
    public function getFormattedAmount()
    {
        return $this->amount != null ? number_format($this->amount/100, 2, ',', ' ') : '---';
    }
    
    /**
     * @param int $amount
     * @return Entry
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return A formatted value of amount
     */
    public function getAmountLabel()
    {
        return number_format($this->amount, 2, ',', '.');
    }

    /**
     * 
     * @return \AccountingBundle\Entity\Project|null
     */
    public function getProject():?Project
    {
        return $this->project;
    }

    /**
     * @param \AccountingBundle\Entity\Project $project
     * @return Entry
     */
    public function setProject($project)
    {
        $this->project = $project;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     * @return Entry
     */
    public function setActive($active)
    {
        $this->active = $active;

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
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Entry
     */
    public function setUpdatedAt(\DateTime $updatedAt): Entry
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    public function getIsPrev()
    {
        return $this->accountingDate == null;
    }
        
    /**
     * 
     * @return \AccountingBundle\Entity\AccountableAccount|null
     */
    public function getAccountableAccount():?AccountableAccount
    {
        return $this->accountableAccount;
    }
    
    /**
     * 
     * @param \AccountingBundle\Entity\AccountableAccount $accountableAccount
     * @return \AccountingBundle\Entity\Entry
     */
    public function setAccountableAccount(AccountableAccount $accountableAccount): Entry
    {
        $this->accountableAccount = $accountableAccount;
        
        return $this;
    }
}
