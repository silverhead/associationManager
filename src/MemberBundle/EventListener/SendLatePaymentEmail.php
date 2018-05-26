<?php

namespace MemberBundle\EventListener;

use AppBundle\Manager\SettingManager;
use AppBundle\Service\MailerTemplating;
use Doctrine\ORM\Event\LifecycleEventArgs;
use MemberBundle\Entity\Member;

class SendLatePaymentEmail
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
//        $entityManager = $args->getEntityManager();

        if ($entity instanceof Member) {
            $associationName = $this->settingManager->getSettingValue("app.setting.association_name");

            $latePaymentEmailSubject = $this->settingManager->getSettingValue("member.email.late_payment_subject");
            $latePaymentEmailBody = $this->settingManager->getSettingValue("member.email.late_payment_body");

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

            $latePaymentEmailSubject = str_replace('{associationName}', $associationName, $latePaymentEmailSubject);
            $latePaymentEmailBody = str_replace($keywordsToReplace, $data, $latePaymentEmailBody);

            $this->mailerTemplating->send(
                ':member/email:latePayment.html.twig',
                ['bodyContent' => nl2br($latePaymentEmailBody)],
                $latePaymentEmailSubject,
                $this->robotEmail,
                $entity->getEmail()
            );
        }
    }
}