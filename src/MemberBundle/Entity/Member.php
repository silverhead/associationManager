<?php

namespace MemberBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @ORM\OneToMany(targetEntity="MemberBundle\Entity\MemberStatusHistorical", mappedBy="member", cascade={"persist"})
     */
    protected $status;

    /**
     * 
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg", "image/gif", "image/png" })
     */
    protected $avatar;
    
    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    protected $birthday;

    /**
     * @var string
     * @ORM\Column(length=10, nullable=true)
     */
    protected $phone;

    /**
     * 
     * @var string
     * @ORM\Column(length=2, nullable=false)
     */
    protected $gender;
    
    /**
     * @var string
     * @ORM\Column(length=10, nullable=true)
     */
    protected $cellular;

    /**
     * 
     * @var string
     * @ORM\Column(length=255, nullable=false)
     */
    protected $country;
    
    /**
     * @var string
     * @ORM\Column(length=255, nullable=false)
     */
    protected $city;
    
    /**
     * @var string
     * @ORM\Column(length=255, nullable=false)
     */
    protected $zipcode;
    
    /**
     * 
     * @var string
     * @ORM\Column(length=255, nullable=false)
     */
    protected $address;
    
    /**
     * @return the $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return the $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return the $zipcode
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @return the $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $zipcode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

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
        return $this;
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
        return $this;
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
        return $this;
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
        $this->subscriptions->add($subscription);

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
        $status->setMember($this);
        $this->status->add($status);

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

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Member
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getCellular()
    {
        return $this->cellular;
    }

    /**
     * @param string $cellular
     * @return Member
     */
    public function setCellular($cellular)
    {
        $this->cellular = $cellular;

        return $this;
    }
    
    /**
     * @param string $gender
     * @return Member
     */
    public function setGender(string $gender)
    {
        $this->gender = $gender;
        return $this;
    }
    
    /**
     * @return string $gender
     */
    public function getGender()
    {
        return $this->gender;
    }
    /**
     * @return \MemberBundle\Entity\unknown
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param \MemberBundle\Entity\unknown $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        
        return $this;
    }

    
    
}
