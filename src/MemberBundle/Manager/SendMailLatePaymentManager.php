<?php

namespace MemberBundle\Manager;

class SendMailLatePaymentManager
{
    /**
     * @var SendMailManagerInterface
     */
    private $sendMailManager;

    /**
     * @var array
     */
    private $placeholderWords;

    public function __construct(SendMailManagerInterface $sendMailManager, array $placeholderWords)
    {
        $this->sendMailManager = $sendMailManager;

        $this->placeholderWords = $placeholderWords;

        $this->sendMailManager->prepareData(
            $placeholderWords,
            'member.email.late_member_notification_email_subject',
            'member.email.late_member_notification_email_body',
            'member/email/latePayment.html.twig'
        );
    }

    public function send($data)
    {
        $this->sendMailManager->send($data);
    }
}