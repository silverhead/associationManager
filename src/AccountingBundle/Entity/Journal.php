<?php

namespace AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Journal
 * @package AccountingBundle\Entity
 * @ORM\Entity(repositoryClass="AccountingBundle\Repository\JournalRepository")
 * @author dominique
 */
class Journal {
    //protected $discr = 'journal';
    
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
     * @var \ArrayCollection|AccountableAccount
     * @ORM\OneToMany(targetEntity="AccountingBundle\Entity\AccountableAccount", fetch="EAGER", mappedBy="journal")
     */
    protected $accounts;

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
    public function setId($id)
    {
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
    public function setCode($code)
    {
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
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return AccountableAccount|\ArrayCollection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param AccountableAccount|\ArrayCollection $accounts
     * @return Journal
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;

        return $this;
    }
}
