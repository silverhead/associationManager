<?php

namespace MemberBundle\Entity;

use AppBundle\Entity\FieldsList;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\Entity\User;
use Symfony\Component\Intl\Intl;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Member
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="MemberBundle\Repository\MemberRepository")
 * @Vich\Uploadable
 */
class Member extends User
{
    protected static $genders = array(
        'm' => 'member.member.edit.form.gender.male',
        'f' => 'member.member.edit.form.gender.female'
    );

    protected $discr = 'member';

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="MemberBundle\Entity\MemberSubscriptionHistorical", mappedBy="member", cascade={"persist"})
     * @ORM\OrderBy({"endDate" = "DESC", "id" = "DESC"})
     */
    protected $subscriptions;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="MemberBundle\Entity\MemberStatusHistorical", mappedBy="member", cascade={"persist"})
     * @ORM\OrderBy({"startDate" = "DESC", "endDate" = "ASC"})
     */
    protected $statusHistorical;

    /**
     * @var ArrayCollection
     */
    protected $status;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="MemberBundle\Entity\MemberSubscriptionFee", mappedBy="member", cascade={"persist"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $fees;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="member_avatar", fileNameProperty="avatar")
     *
     * @var File
     */
    private $avatarFile;

    /**
     * 
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @ORM\Column(length=255, nullable=false)
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
     * @var array
     * @ORM\Column(type="array", nullable=false)
     */
    protected $expertise;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $study;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statusHistorical = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fees = new \Doctrine\Common\Collections\ArrayCollection();

        $this->avatar = 'user.png';

        $this->active = true;
    }

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
     * Add subscription
     *
     * @param \MemberBundle\Entity\MemberSubscriptionHistorical $subscription
     *
     * @return Member
     */
    public function addSubscription(\MemberBundle\Entity\MemberSubscriptionHistorical $subscription)
    {
        $subscription->setMember($this);

        $periodicity = $subscription->getSubscriptionPaymentPeriodicity();

        $nbCotisations = ceil($subscription->getSubscription()->getDuration() / $periodicity->getDuration());

        $dayByFee = ceil($subscription->getSubscription()->getDuration() / $nbCotisations);

        for($i=0; $i<$nbCotisations;$i++){
            $startDate = clone $subscription->getStartDate();
            $cost = ceil($subscription->getCost() / $nbCotisations);

            if($i > 0){
                $cost = floor($subscription->getCost() / $nbCotisations);
                $dayByFee = ceil($subscription->getSubscription()->getDuration() / $nbCotisations);
                $dayStart = $dayByFee * $i;
                $startDate->add(new \DateInterval("P" .$dayStart . "D"));
            }

            $endDate = clone $startDate;
            $endDate->add(new \DateInterval("P" . ($dayByFee - 1) . "D"));

            $fee = new MemberSubscriptionFee();
            $fee->setMember($this)
                ->setSubscription($subscription)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setCost($cost)
            ;

            $this->addFee($fee);
        }


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
     * @return ArrayCollection
     */
    public function getStatus()
    {
        $status = new ArrayCollection();
        foreach($this->statusHistorical as $statusHist){
            $status->add($statusHist);
        }
        return $status;
    }

    /**
     * @param ArrayCollection $status
     */
    public function setStatus($status)
    {
        $statusHistoricalOld = $this->statusHistorical->first();

        dump($statusHistoricalOld);
        dump($status);

        if($statusHistoricalOld == null || $statusHistoricalOld->getStatus() == null || $statusHistoricalOld->getStatus()->getId() != $status->getId()){
            $statusHistorical = new MemberStatusHistorical();
            $statusHistorical->setStatus($status)
                ->setMember($this)
                ->setStartDate(new \DateTime());

            $this->statusHistorical->add($statusHistorical);

            $this->statusHistorical->removeElement($statusHistoricalOld);
            $statusHistoricalOld->setEndDate(new \DateTime());

            $this->statusHistorical->add($statusHistoricalOld);
        }

        return $this;
    }

//    /**
//     * @return ArrayCollection
//     */
//    public function getStatusHistorical(){
//        return $this->statusHistorical;
//    }
//
//    public function setStatusHistorical(ArrayCollection $statusHistorical){
//        $this->statusHistorical = $statusHistorical;
//        return $this;
//    }

    /**
     * Add status
     *
     * @param \MemberBundle\Entity\MemberStatusHistorical $status
     *
     * @return Member
     */
    public function addStatusHistorical(\MemberBundle\Entity\MemberStatusHistorical $statusHistorical)
    {
        $statusHistorical->setMember($this);
        $this->statusHistorical->add($statusHistorical);

        return $this;
    }

    /**
     * Remove status
     *
     * @param \MemberBundle\Entity\MemberStatusHistorical $status
     */
    public function removeStatusHistorical(\MemberBundle\Entity\MemberStatusHistorical $statusHistorical)
    {
        $this->statusHistorical->removeElement($statusHistorical);
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
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFees(): Collection
    {
        return $this->fees;
    }

    /**
     * @param ArrayCollection $fees
     * @return Member
     */
    public function setFees(Collection $fees): Member
    {
        $this->fees = $fees;

        return $this;
    }



    public function getFullGender(){
        return self::$genders[$this->gender];
    }

    public function getFullCountryName(){
        return Intl::getRegionBundle()->getCountryName($this->country);
    }

    /**
     * Add fee.
     *
     * @param \MemberBundle\Entity\MemberSubscriptionFee $fee
     *
     * @return Member
     */
    public function addFee(\MemberBundle\Entity\MemberSubscriptionFee $fee)
    {
        $this->fees[] = $fee;

        return $this;
    }

    /**
     * Remove fee.
     *
     * @param \MemberBundle\Entity\MemberSubscriptionFee $fee
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFee(\MemberBundle\Entity\MemberSubscriptionFee $fee)
    {
        return $this->fees->removeElement($fee);
    }

    /**
     * Get statusHistorical.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatusHistorical()
    {
        return $this->statusHistorical;
    }

    public function getLastStatus():? MemberStatus
    {

        if(isset($this->getStatus()[0])){
            return $this->getStatus()[0]->getStatus();
        }

        return null;
    }

    /**
     * @return File
     */
    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * @param File $avatarFile
     * @return Member
     */
    public function setAvatarFile(?File $avatarFile = null): Member
    {
        $this->avatarFile = $avatarFile;

        if($avatarFile){
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getExpertise():? array
    {
        return $this->expertise;
    }

    /**
     * @param array $expertise
     * @return Member
     */
    public function setExpertise(array $expertise): Member
    {
        $this->expertise = $expertise;

        return $this;
    }

    /**
     * @return string
     */
    public function getStudy():? string
    {
        return $this->study;
    }

    /**
     * @param string $study
     * @return Member
     */
    public function setStudy(string $study): Member
    {
        $this->study = $study;

        return $this;
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
     * @return Member
     */
    public function setUpdatedAt(\DateTime $updatedAt): Member
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
