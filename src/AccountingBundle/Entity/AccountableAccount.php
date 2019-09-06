<?php

namespace AccountingBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="AccountingBundle\Entity\Journal", fetch="EAGER", inversedBy="entries")
     * ORM\JoinColumn(name="journal_id", referencedColumnName="id")
     */
    protected $journal;
    
    /**
     * @var \ArrayObject|Entry
     * @ORM\OneToMany(targetEntity="AccountingBundle\Entity\Entry", fetch="EAGER", mappedBy="accountableAccount")
     */
    protected $entries;
    
    
    public function __construct() {
        $this->entries = new ArrayCollection();
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
     * 
     * @return \AccountingBundle\Entity\Journal|null
     */
    public function getJournal():?Journal {
        return $this->journal;
    }
    
    /**
     * 
     * @return PersistentCollection|null
     */
    public function getEntries(): PersistentCollection {
        return $this->entries;
    }
}
