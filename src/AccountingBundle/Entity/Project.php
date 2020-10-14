<?php

namespace AccountingBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Project
 * @package AccountingBundle\Entity
 * @ORM\Entity(repositoryClass="AccountingBundle\Repository\ProjectRepository")
 */
class Project
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
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $active;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var \ArrayObject|Entry
     * @ORM\OneToMany(targetEntity="AccountingBundle\Entity\Entry", fetch="EAGER", mappedBy="project")
     */
    protected $entries;
    
    public function __construct()
    {
        $this->entries = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
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
     * @return Project 
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

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
     * @return Project
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
     * @return Project
     */
    public function setUpdatedAt(\DateTime $updatedAt): Project
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    /**
     * 
     * @return Collection|null
     */
    public function getEntries():?Collection
    {
        return $this->entries;
    }
}
