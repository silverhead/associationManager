<?php

namespace AppBundle\Service;

use AppBundle\Manager\SettingManager;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class MailerTemplating
 *
 * Cette classe permet d'envoyer des e-mails avec un template twig améliorant le visuel de l'e-mail
 *
 * @package AppBundle\Services
 * @author NICOLAS PIN <pin.nicolas@free.fr>
 *
 */
class MailerTemplating
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var SettingManager
     */
    private $settingManager;

    /**
     * MailerTemplating constructor.
     * @param \Swift_Mailer $mailer le service d'envoi d'email
     * @param EngineInterface $templating le service de templating
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, SettingManager $settingAppManager )
    {
        $this->mailer = $mailer;
        $this->templating = $templating;

        $this->settingManager = $settingAppManager;
    }

    /***
     * Send
     *
     * Envoie un e-mail avec un template html
     *
     * @param string $template le template html
     * @param array $bodyContents le coprs du message
     * @param string $subject le sujet du message
     * @param string $from l'e-mail qui envoie
     * @param string $to l'e-mail qui reçoit
     * @return int nombre d'e-mail envoyé
     */
    public function send($template, array $bodyContents, $subject, $from, $to)
    {
        $appData = array(
            'associationName' => $this->settingManager->getSettingValue('app.setting.association_name'),
            'logo' => $this->settingManager->getSettingValue('app.setting.logo'),
            'urlFacebook' => $this->settingManager->getSettingValue('app.setting.url_facebook'),
            'urlTwitter' => $this->settingManager->getSettingValue('app.setting.url_twitter'),
        );

        $bodyContents = array_merge($bodyContents, $appData);

        $body = $this->templating->render($template, $bodyContents);
        $message = \Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body)
        ;

        return $this->mailer->send($message);
    }
}