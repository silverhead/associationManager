<?php

namespace MemberBundle\EventListener;

use AppBundle\Manager\SettingManager;
use AppBundle\Service\MailerTemplating;
use Doctrine\ORM\Event\LifecycleEventArgs;
use MemberBundle\Entity\Member;

class SendWelcomeEmail
{
    /**
     * @var SettingManager
     */
    private $settingManager;

    /**
     * @var MailerTemplating
     */
    private $mailerTemplating;

    /**
     * @var string
     */
    private $robotEmail;

    public function __construct(SettingManager $settingManager, MailerTemplating $mailerTemplating, string $robotEmail)
    {
        $this->settingManager = $settingManager;

        $this->mailerTemplating = $mailerTemplating;

        $this->robotEmail = $robotEmail;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Member) {
            $associationName = $this->settingManager->getSettingValue("app.setting.association_name");

            $welcomeEmailSubject = $this->settingManager->getSettingValue("member.email.welcome_subject");
            $welcomeEmailBody = $this->settingManager->getSettingValue("member.email.welcome_body");

            $keywordsToReplace = array(
                '{gender}',
                '{lastName}',
                '{firstName}',
                '{associationName}'
            );

            $data = array(
                $entity->getGender(),
                $entity->getLastName(),
                $entity->getFirstName(),
                $associationName
            );

            $welcomeEmailSubject = str_replace('{associationName}', $associationName, $welcomeEmailSubject);
            $welcomeEmailBody = str_replace($keywordsToReplace, $data, $welcomeEmailBody);

            $this->mailerTemplating->send(
                ':member/email:welcome.html.twig',
                ['bodyContent' => nl2br($welcomeEmailBody)],
                $welcomeEmailSubject,
                $this->robotEmail,
                $entity->getEmail()
            );
        }
    }
}