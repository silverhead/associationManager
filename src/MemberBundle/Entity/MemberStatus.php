<?php

namespace MemberBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MemberStatus
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\MemberStatusRepository")
 */
class MemberStatus
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="MemberBundle\Entity\MemberStatusHistorical", mappedBy="status")
     */
    private $members;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MemberStatus
     */
    public function setId(int $id): MemberStatus
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return MemberStatus
     */
    public function setLabel(string $label): MemberStatus
    {
        $this->label = $label;

        return $this;
    }
    
    /**
     * @param ArrayCollection
     */
    public function setMembers(ArrayCollection $members)
    {
        $this->members = $members;
        
        return $this;
    }
    
    /**
     * @param ArrayCollection
     */
    public function getMembers()
    {
        return $this->members;
    }
    
    /**
     * @param Member
     */
    public function addMember(MemberStatusHistorical $member)
    {
        $member->setStatus($this);
        $this->members->add($member);
        
        return $this;
    }
    
    /**
     * @param Member
     */
    public function removeMember(MemberStatusHistorical $member)
    {
        $this->members->remove($member);
        
        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
