<?php

namespace MemberBundle\Form\Model;


class MemberSettingModel
{
    /**
     * @var string
     */
    private $gender;

    /**
     * @var string
     */
    private $study;

    /**
     * @var string
     */
    private $expertise;

    /**
     * @return string
     */
    public function getExpertise():? string
    {
        return $this->expertise;
    }

    /**
     * @param string $expertise
     * @return MemberSettingModel
     */
    public function setExpertise(string $expertise): MemberSettingModel
    {
        $this->expertise = $expertise;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender():? string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return MemberSettingModel
     */
    public function setGender(string $gender): MemberSettingModel
    {
        $this->gender = $gender;

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
     * @return MemberSettingModel
     */
    public function setStudy(string $study): MemberSettingModel
    {
        $this->study = $study;

        return $this;
    }


}