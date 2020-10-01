<?php
namespace AccountingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Exercise
 * @package AccountingBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AccountingBundle\Repository\ExerciseRepository")
 */
class Exercise {
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
    protected $dateStart;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateEnd;
    
    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    protected $reference;
    
    /**
     * @var AccountableAccount
     * @ORM\ManyToMany(targetEntity="AccountingBundle\Entity\AccountableAccount", inversedBy="exercises", fetch="EXTRA_LAZY")
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
    public function setId($id): Exercise
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
    public function setReference($reference): Exercise
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart()//: \DateTime
    {
        return $this->dateStart;
    }

    /**
     * @param \DateTime $dateStart
     * @return Exercise
     */
    public function setDateStart(\DateTime $dateStart = null): Exercise
    {
        $this->dateStart = $dateStart;

        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getDateEnd()//: \DateTime
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime $dateEnd
     * @return Exercise
     */
    public function setDateEnd(\DateTime $dateEnd = null): Exercise
    {
        $this->dateEnd = $dateEnd;

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
    public function setActive($active): Exercise
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
    public function setUpdatedAt(\DateTime $updatedAt): Exercise
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
   
    /**
     * 
     * @return PersistentCollection|null
     */
//    public function getEntries(): PersistentCollection {
//        return $this->entries;
//    }
}
