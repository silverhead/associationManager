<?php
namespace MemberBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MemberGroup
 *
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\MemberGroupRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberGroup
{
    /**
     * @var int
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private $nbMembers;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="MemberBundle\Entity\Member", inversedBy="memberGroups")
     */
    private $members;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): MemberGroup
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return MemberGroup
     */
    public function setLabel(string $label): MemberGroup
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbMembers(): ?int
    {
        return $this->nbMembers;
    }

    /**
     * @param int $nbMembers
     * @return MemberGroup
     */
    public function setNbMembers(int $nbMembers): MemberGroup
    {
        $this->nbMembers = $nbMembers;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();

        $this->nbMembers = $this->members->count();
    }

    /**
     * Add member.
     *
     * @param \MemberBundle\Entity\Member $member
     *
     * @return MemberGroup
     */
    public function addMember(\MemberBundle\Entity\Member $member)
    {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member.
     *
     * @param \MemberBundle\Entity\Member $member
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMember(\MemberBundle\Entity\Member $member)
    {
        return $this->members->removeElement($member);
    }

    /**
     * @param  $members
     * @return MemberGroup
     */
    public function setMembers($members): MemberGroup
    {
        $this->members = $members;

        return $this;
    }

    /**
     * Get members.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function countNbMembers()
    {
        $this->nbMembers = $this->members->count();
    }
}
