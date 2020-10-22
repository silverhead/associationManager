<?php

namespace AccountingBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Project
 * @package AccountingBundle\Entity
 * @ORM\Entity(repositoryClass="AccountingBundle\Repository\AccountableAccountRepository")
 * @author dominique
 */
class AccountableAccount {
    
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
    protected $code;
    
    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    protected $label;
    
    /**
     * @var Journal
     * @ORM\ManyToOne(targetEntity="AccountingBundle\Entity\Journal", fetch="EAGER", inversedBy="accounts")
     * ORM\JoinColumn(name="journal_id", referencedColumnName="id")
     */
    protected $journal;
    
    /**
     * @var \ArrayCollection|Entry
     * @ORM\OneToMany(targetEntity="AccountingBundle\Entity\Entry", fetch="EAGER", mappedBy="accountableAccount")
     */
    protected $entries;
    
    /**
     * @var \ArrayCollection|Exercise
     * @ORM\ManyToMany(targetEntity="AccountingBundle\Entity\Exercise", fetch="EAGER", mappedBy="accountableAccount")
     */
    protected $exercises;
    
    /**
     *
     * @var int
     */
    protected $realSolde;
    
    /**
     *
     * @var int
     */
    protected $prevSolde;
    
    /**
     * @var \Collection|Solde
     * @ORM\OneToMany(targetEntity="AccountingBundle\Entity\Solde", fetch="EAGER", mappedBy="accountableAccount")
     */
    protected $soldes;
        
    public function __construct() {
        $this->entries = new ArrayCollection();
        $this->soldes = new ArrayCollection();
    }
    
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @param int $id
     * @return AccountableAccount
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getCode() {
        return $this->code;
    }
    
    /**
     * @param string $code
     * @return AccountableAccount
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }
    
    /**
     * @param string $label
     * @return AccountableAccount
     */
    public function setLabel($label) {
        $this->label = $label;

        return $this;
    }

    /**
     * @param Collection $entries
     * @return AccountableAccount 
     */
    public function populateEntries($entries) {
        $this->entries = $entries;
        
        return $this;
    }
    
    /**
     * 
     * @return ArrayCollection|null
     */
    public function getEntries(): ArrayCollection {
        return is_array($this->entries) ? new ArrayCollection($this->entries) : ($this->entries instanceof PersistentCollection ? new ArrayCollection($this->entries->getValues()) : $this->entries);
    }
    
    /**
     * 
     * @return Collection|null
     */
    public function getExercises(): Collection {
        return $this->exercises;
    }
    
    /**
     * 
     * @return Collection|null
     */
    public function getSoldes(): Collection {
        return $this->soldes;
    }
    public function getSoldesSortedByDate() {
        $arraySoldes = $this->soldes->getValues();
        if (count($arraySoldes) > 0) {
            usort($arraySoldes, function ($object1, $object2) { 
                return $object1->getDate() < $object2->getDate(); 
            });
        }
        return $arraySoldes;
    }
    
    public function getLastSolde($isPrev = false) {
        $arraySoldes = $this->soldes->getValues();
        if (count($arraySoldes) > 0) {
            usort($arraySoldes, function ($object1, $object2) { 
                return $object1->getDate() < $object2->getDate(); 
            });
            $soldeToReturn = null;
            foreach ($arraySoldes as $solde) {
                if ($solde->getIsPrev() == $isPrev) {
                    $soldeToReturn = $solde;
                } else {
                    $soldeToReturn = $solde;
                }
                break;
            }
        } else {
            $soldeToReturn = new Solde();
        } 
        
        return $soldeToReturn;
    }

    /**
     * @return \AccountingBundle\Entity\AccountableAccount
     */
    public function setJournal($journal):self {
        $this->journal = $journal;
        return $this;
    }

    /**
     * @return \AccountingBundle\Entity\Journal|null
     */
    public function getJournal():?Journal {
        return $this->journal;
    }
    
    public function getCalculatedLastSolde() {
        $solde = $this->getLastSolde(false);
        $amountOfEntries = 0;
        foreach ($this->getEntries() as $entry) {
            if ($entry->getAccountingDate() > $solde->getDate()) {
                $amountOfEntries += $entry->getAmount();
            }
        }
        $solde->setAmount($solde->getAmount() + $amountOfEntries);
        return $solde;
    }
}
