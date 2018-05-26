<?php

namespace MemberBundle\EventListener;

use AppBundle\Manager\SettingManager;
use AppBundle\Service\MailerTemplating;
use Doctrine\ORM\Event\LifecycleEventArgs;
use MemberBundle\Entity\Member;
use MemberBundle\Entity\MemberSubscriptionHistorical;

class SendNewSubscriptionEmail
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

        if ($entity instanceof MemberSubscriptionHistorical) {
            $associationName = $this->settingManager->getSettingValue("app.setting.association_name");

            $newSubscriptionEmailSubject = $this->settingManager->getSettingValue("member.email.new_subscription_subject");
            $newSubscriptionEmailBody = $this->settingManager->getSettingValue("member.email.new_subscription_body");

            $keywordsToReplace = array(
                '{gender}',
                '{lastName}',
                '{firstName}',
                '{subscriptionLabel}',
                '{startDate}',
                '{endDate}',
                '{totalCost}',
                '{associationName}'
            );

            $data = array(
                $entity->getMember()->getGender(),
                $entity->getMember()->getLastName(),
                $entity->getMember()->getFirstName(),
                $entity->getSubscription()->getLabel(),
                $entity->getStartDate()->format('d/m/Y'),
                $entity->getEndDate()->format('d/m/Y'),
                number_format($entity->getCost(), 2, ",", " "),
                $associationName
            );

            $newSubscriptionEmailSubject = str_replace('{associationName}', $associationName, $newSubscriptionEmailSubject);
            $newSubscriptionEmailSubject = str_replace('{subscriptionLabel}', $entity->getSubscription()->getLabel(), $newSubscriptionEmailSubject);
            $newSubscriptionEmailBody = str_replace($keywordsToReplace, $data, $newSubscriptionEmailBody);

            $this->mailerTemplating->send(
                ':member/email:newSubscription.html.twig',
                ['bodyContent' => nl2br($newSubscriptionEmailBody)],
                $newSubscriptionEmailSubject,
                $this->robotEmail,
                $entity->getMember()->getEmail()
            );
        }
    }
}