<?php

namespace MemberBundle\Entity;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

class MemberImport
{
    const MIN_COLUMN_LENGTH = 9;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $gender;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $lastName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $birthday;

    /**
     * @var string
     * @Assert\Country()
     */
    private $countryCode;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $city;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $zipCode;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private $address;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $cellular;

    /**
     * @var string
     */
    private $studyLevel;

    /**
     * @var string
     */
    private $specialities;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var string
     */
    private $groups;

    /**
     * @var int
     */
    private $numLine;

    /**
     * @var array
     */
    private $numColumnByProperty = array(
        'gender' => 1,
        'lastName' => 2,
        'firstName' => 3,
        'email' => 4,
        'birthday' => 5,
        'countryCode' => 6,
        'city' => 7,
        'zipCode' => 8,
        'address' => 9,
        'phone' => 10,
        'cellular' => 11,
        'studyLevel' => 12,
        'specialities' => 13,
        'comment' => 14,
        'groups' => 15
    );

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return MemberImport
     */
    public function setGender(string $gender): MemberImport
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return MemberImport
     */
    public function setLastName(string $lastName): MemberImport
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return MemberImport
     */
    public function setFirstName(string $firstName): MemberImport
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return MemberImport
     */
    public function setEmail(string $email): MemberImport
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     * @return MemberImport
     */
    public function setBirthday(string $birthday): MemberImport
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return MemberImport
     */
    public function setCountryCode(string $countryCode): MemberImport
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return MemberImport
     */
    public function setCity(string $city): MemberImport
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return MemberImport
     */
    public function setZipCode(string $zipCode): MemberImport
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return MemberImport
     */
    public function setAddress(string $address): MemberImport
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return MemberImport
     */
    public function setPhone(string $phone): MemberImport
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getCellular(): string
    {
        return $this->cellular;
    }

    /**
     * @param string $cellular
     * @return MemberImport
     */
    public function setCellular(string $cellular): MemberImport
    {
        $this->cellular = $cellular;

        return $this;
    }

    /**
     * @return string
     */
    public function getStudyLevel(): string
    {
        return $this->studyLevel;
    }

    /**
     * @param string $studyLevel
     * @return MemberImport
     */
    public function setStudyLevel(string $studyLevel): MemberImport
    {
        $this->studyLevel = $studyLevel;

        return $this;
    }

    /**
     * @return string
     */
    public function getSpecialities(): string
    {
        return $this->specialities;
    }

    /**
     * @param string $specialities
     * @return MemberImport
     */
    public function setSpecialities(string $specialities): MemberImport
    {
        $this->specialities = $specialities;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return MemberImport
     */
    public function setComment(string $comment): MemberImport
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroups(): string
    {
        return $this->groups;
    }

    /**
     * @param string $groups
     * @return MemberImport
     */
    public function setGroups(string $groups): MemberImport
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumLine(): int
    {
        return $this->numLine;
    }

    /**
     * @param int $numLine
     * @return MemberImport
     */
    public function setNumLine(int $numLine): MemberImport
    {
        $this->numLine = $numLine;

        return $this;
    }

    /**
     * @return array
     */
    public function getNumColumnByProperty(): array
    {
        return $this->numColumnByProperty;

        return $numColumnByProperty;
    }

    /**
     * @return array
     */
    public function getNumColumnOfProperty(string $propertyName): string
    {
        if (!isset($this->numColumnByProperty[$propertyName])){
            throw new \Exception('Not number found for the propertyName "'.$propertyName.'"');
        }

        return $this->numColumnByProperty[$propertyName];
    }

}