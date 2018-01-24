<?php

namespace MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MemberStatusHistorical
 * @package MemberBundle\Entity
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\MemberSubscriptionHistoricalRepository")
 */
class MemberStatusHistorical
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var Member
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\Member", inversedBy="status")
     *
     */
    private $member;

    /**
     * @var MemberStatus
     * @ORM\ManyToOne(targetEntity="MemberBundle\Entity\MemberStatus", inversedBy="members")
     */
    private $status;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private $endDate;

    public function __construct()
    {
        $this->startDate = new \DateTime();
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
     * @return MemberStatusHistorical
     */
    public function setId(int $id): MemberStatusHistorical
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @param Member $member
     * @return MemberStatusHistorical
     */
    public function setMember(Member $member): MemberStatusHistorical
    {
        $this->member = $member;

        return $this;
    }

    /**
     * @return MemberStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param MemberStatus $status
     * @return MemberStatusHistorical
     */
    public function setStatus(MemberStatus $status): MemberStatusHistorical
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     * @return MemberStatusHistorical
     */
    public function setStartDate(\DateTime $startDate): MemberStatusHistorical
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     * @return MemberStatusHistorical
     */
    public function setEndDate(\DateTime $endDate): MemberStatusHistorical
    {
        $this->endDate = $endDate;

        return $this;
    }
}
