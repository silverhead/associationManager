<?php

namespace AppBundle\Manager;

use MemberBundle\Manager\SendMailManagerInterface;

class SendMailManager implements SendMailManagerInterface
{
    /**
     * @var SettingManager
     */
    private $setting;

    /**
     * @var MailerTemplating
     */
    private $mailerTemplating;

    /**
     * @var array
     */
    private $placeholderWords;

    /**
     * @var string
     */
    private $subjectCode;

    /**
     * @var string
     */
    private $bodyCode;

    /**
     * @var string
     */
    private $templatePath;

    public function __construct(SettingManager $setting, MailerTemplating $mailerTemplating)
    {
        $this->setting = $setting;

        $this->mailerTemplating = $mailerTemplating;
    }

    public function prepareCustomData(array $placeholderWords, string $subjectCode, string $bodyCode, string $templatePath)
    {
        $this->placeholderWords = $placeholderWords;

        $this->subjectCode = $subjectCode;

        $this->bodyCode = $bodyCode;

        $this->templatePath = $templatePath;
    }

    public function send(array $data)
    {
        $associationName = $this->settingManager->getSettingValue("app.setting.association_name");

        $from = $this->setting->getSettingValue('app.setting.robot_email');

        $subject = $this->prepareSubject($data, $associationName);
        $body = $this->prepareBody($data, $associationName);

        $this->mailerTemplating->send(
            $this->templatePath,
            $body,
            $subject,
            $from,
            $data['email']
        );
    }

    private function prepareSubject($data, $associationName)
    {
        $subject = $this->setting->getFormatedSettingValue($this->subjectCode);

        $subject = str_replace($subject,'associationName', $associationName);

        foreach ($this->placeholderWords as $keyword => $property) {
            $subject = str_replace($subject,'{' .$keyword . '}', $data[$property]);
        }

        return $subject;
    }

    private function prepareBody($data)
    {
        $body = $this->setting->getFormatedSettingValue($this->bodyCode);

        foreach ($this->placeholderWords as $keyword => $property) {
            $body = str_replace($body,'{' .$keyword . '}', $data[$property]);
        }

        return $body;
    }
}