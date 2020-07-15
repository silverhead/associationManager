<?php

namespace AppBundle\Form\Model;

use AppBundle\Entity\EmailSetting;

class EmailSettingModel
{
    /**
     * @var EmailSetting
     */
    private $emailType;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body;

    /**
     * @return EmailSetting
     */
    public function getEmailType(): ?EmailSetting
    {
        return $this->emailType;
    }

    /**
     * @param EmailSetting $emailType
     * @return EmailSettingModel
     */
    public function setEmailType(EmailSetting $emailType): EmailSettingModel
    {
        $this->emailType = $emailType;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return EmailSettingModel
     */
    public function setSubject(string $subject): EmailSettingModel
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return EmailSettingModel
     */
    public function setBody(string $body): EmailSettingModel
    {
        $this->body = $body;

        return $this;
    }


}