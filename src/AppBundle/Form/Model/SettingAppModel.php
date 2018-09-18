<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 27/04/2017
 * Time: 22:06
 */

namespace AppBundle\Form\Model;


class SettingAppModel
{
    private $logo;

    private $associationName;

    private $contactEmail;

    private $phone;

    private $gsm;

    private $country;

    private $city;

    private $zipcode;

    private $address;

    private $description;

    private $applicationName;

    private $applicationDescription;

    /**
     * @var string
     */
    private $urlFacebook;

    /**
     * @var string
     */
    private $urlTwitter;

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     * @return SettingAppModel
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssociationName()
    {
        return $this->associationName;
    }

    /**
     * @param mixed $associationName
     * @return SettingAppModel
     */
    public function setAssociationName($associationName)
    {
        $this->associationName = $associationName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param mixed $contactEmail
     * @return SettingAppModel
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return SettingAppModel
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGsm()
    {
        return $this->gsm;
    }

    /**
     * @param mixed $gsm
     * @return SettingAppModel
     */
    public function setGsm($gsm)
    {
        $this->gsm = $gsm;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return SettingAppModel
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return SettingAppModel
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param mixed $zipcode
     * @return SettingAppModel
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return SettingAppModel
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return SettingAppModel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlFacebook()
    {
        return $this->urlFacebook;
    }

    /**
     * @param string $urlFacebook
     * @return SettingAppModel
     */
    public function setUrlFacebook($urlFacebook)
    {
        $this->urlFacebook = $urlFacebook;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlTwitter()
    {
        return $this->urlTwitter;
    }

    /**
     * @param string $urlTwitter
     * @return SettingAppModel
     */
    public function setUrlTwitter($urlTwitter)
    {
        $this->urlTwitter = $urlTwitter;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * @param mixed $applicationName
     * @return SettingAppModel
     */
    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApplicationDescription()
    {
        return $this->applicationDescription;
    }

    /**
     * @param mixed $applicationDescription
     * @return SettingAppModel
     */
    public function setApplicationDescription($applicationDescription)
    {
        $this->applicationDescription = $applicationDescription;

        return $this;
    }
}