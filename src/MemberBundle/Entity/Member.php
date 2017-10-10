<?php

namespace MemberBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\Entity\User;

/**
 * Class Member
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\MemberRepository")
 */
class Member extends User
{
    protected $discr = 'member';

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="MemberBundle\Entity\MemberSubscriptionHistorical", mappedBy="member")
     */
    protected $subscriptions;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="MemberBundle\Entity\MemberStatusHistorical", mappedBy="member")
     */
    protected $status;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    protected $birthday;

    /**
     * @return ArrayCollection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param ArrayCollection $subscriptions
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @return ArrayCollection
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param ArrayCollection $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param DateTime $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subscription
     *
     * @param \MemberBundle\Entity\MemberSubscriptionHistorical $subscription
     *
     * @return Member
     */
    public function addSubscription(\MemberBundle\Entity\MemberSubscriptionHistorical $subscription)
    {
        $this->subscriptions[] = $subscription;

        return $this;
    }

    /**
     * Remove subscription
     *
     * @param \MemberBundle\Entity\MemberSubscriptionHistorical $subscription
     */
    public function removeSubscription(\MemberBundle\Entity\MemberSubscriptionHistorical $subscription)
    {
        $this->subscriptions->removeElement($subscription);
    }

    /**
     * Add status
     *
     * @param \MemberBundle\Entity\MemberStatusHistorical $status
     *
     * @return Member
     */
    public function addStatus(\MemberBundle\Entity\MemberStatusHistorical $status)
    {
        $this->status[] = $status;

        return $this;
    }

    /**
     * Remove status
     *
     * @param \MemberBundle\Entity\MemberStatusHistorical $status
     */
    public function removeStatus(\MemberBundle\Entity\MemberStatusHistorical $status)
    {
        $this->status->removeElement($status);
    }
}
