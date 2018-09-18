<?php

namespace AppBundle\Manager;

use AppBundle\Service\MailerTemplating;
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
     * @var string
     */
    private $robotEmail;

    /**
     * @var array
     */
    private $placeholderWords;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $templatePath;

    /**
     * @var string
     */
    private $dateFormat;

    public function __construct(SettingManager $setting, MailerTemplating $mailerTemplating, $robotEmail)
    {
        $this->setting = $setting;

        $this->mailerTemplating = $mailerTemplating;

        $this->robotEmail = $robotEmail;
    }

    /**
     * @param array $placeholderWords
     * @param string $subject
     * @param string $body
     * @param string $templatePath
     */
    public function prepareData(array $placeholderWords, string $subject, string $body, string $templatePath, string $dateFormat): void
    {
        $this->placeholderWords = $placeholderWords;

        $this->subject = $subject;

        $this->body = $body;

        $this->templatePath = $templatePath;

        $this->dateFormat = $dateFormat;
    }

    /**
     * @param array $data
     * @return int
     * @throws \Exception
     */
    public function send(array $data): int
    {
        $associationName = $this->setting->getSettingValue("app.setting.association_name");

        $from = $this->robotEmail;

        $subject = $this->prepareSubject($data, $associationName);
        $body = $this->prepareBody($data, $associationName);

        return $this->mailerTemplating->send(
            $this->templatePath,
            ['bodyContent' => nl2br($body)],
            $subject,
            $from,
            $data['email']
        );
    }

    /**
     * @param $data
     * @param $associationName
     * @return string
     * @throws \Exception
     */
    private function prepareSubject($data, $associationName): string
    {
        $subject = $this->subject;

        $subject = str_replace('{associationName}', $associationName, $subject);

        foreach ($this->placeholderWords as $keyword => $property) {

            $value = $data[$property];

            if ($value instanceof \DateTime)
            {
                $value = $value->format($this->dateFormat);
            }

            $subject = str_replace('{' .$keyword . '}', $value, $subject);
        }

        return $subject;
    }

    /**
     * @param $data
     * @param $associationName
     * @return string
     * @throws \Exception
     */
    private function prepareBody($data, $associationName): string
    {
        $body = $this->body;

        $body = str_replace('{associationName}', $associationName, $body);

        foreach ($this->placeholderWords as $keyword => $property) {
            $value = $data[$property];

            if ($value instanceof \DateTime)
            {
                $value = $value->format($this->dateFormat);
            }

            $body = str_replace('{' .$keyword . '}', $value, $body);
        }

        return $body;
    }
}