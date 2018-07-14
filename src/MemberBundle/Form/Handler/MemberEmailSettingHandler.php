<?php

namespace MemberBundle\Form\Handler;

use MemberBundle\Form\Model\MemberEmailSettingModel;
use MemberBundle\Form\Type\MemberEmailSettingFormType;
use AppBundle\Manager\SettingManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class MemberEmailSettingHandler
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    private $form;

    /**
     * @var MemberSettingModel
     */
    private $memberEmailSettingModel;

    /**
     * @var SettingManager
     */
    private $settingManager;

    public function __construct(FormFactory $formFactory, MemberEmailSettingModel  $memberEmailSettingModel, SettingManager $settingManager){
        $this->formFactory = $formFactory;
        $this->memberEmailSettingModel = $memberEmailSettingModel;
        $this->settingManager = $settingManager;
    }

    public function setForm()
    {
        $this->initSettingModel();
        $this->form = $this->formFactory->create(MemberEmailSettingFormType::class, $this->memberEmailSettingModel);
    }

    public function getForm()
    {
        if(null === $this->form){
            throw new Exception("the form is not init, please use ::setForm before!");
        }

        return $this->form;
    }

    public function initSettingModel()
    {
        $this->memberEmailSettingModel
            ->setWelcomeEmailSubject(
                $this->settingManager->getFormatedSettingValue('member.email.welcome_subject')
            )
            ->setWelcomeEmailBody(
                nl2br($this->settingManager->getFormatedSettingValue('member.email.welcome_body'))
            )
            ->setNewSubscriptionEmailSubject(
                $this->settingManager->getFormatedSettingValue('member.email.new_subscription_subject')
            )
            ->setNewSubscriptionEmailBody(
                nl2br($this->settingManager->getFormatedSettingValue('member.email.new_subscription_body'))
            )
            ->setNewFeeComingSoonEmailSubject(
                $this->settingManager->getFormatedSettingValue('member.email.new_fee_coming_soon_email_subject')
            )
            ->setNewFeeComingSoonEmailBody(
                nl2br($this->settingManager->getFormatedSettingValue('member.email.new_fee_coming_soon_email_body'))
            )
            ->setLateMemberNotificationEmailSubject(
                $this->settingManager->getFormatedSettingValue('member.email.late_member_notification_email_subject')
            )
            ->setLateMemberNotificationEmailBody(
                nl2br($this->settingManager->getFormatedSettingValue('member.email.late_member_notification_email_body'))
            )
            ->setNewFeeComingSoonEmailSendingDelay(
                $this->settingManager->getFormatedSettingValue('subscription.delay.new_fee_coming_soon_email_sending')
            )
            ->setLatePaymentMemberEmailSendingDelay(
                $this->settingManager->getFormatedSettingValue('subscription.delay.late_payment_member_email_sending')
            )
        ;
    }

    public function process(Request $request)
    {
        $this->form->handleRequest($request);

        if($this->form->isSubmitted() && $this->form->isValid()){
            $this->saveSetting();

            return true;
        }

        return false;
    }

    private function saveSetting()
    {
        /**
         * @var MemberEmailSettingModel
         */
        $data = $this->form->getData();

        $this->settingManager->save('member.email.welcome_subject', $data->getWelcomeEmailSubject());
        $this->settingManager->save('member.email.welcome_body', $data->getWelcomeEmailBody());
        $this->settingManager->save('member.email.new_subscription_subject', $data->getNewSubscriptionEmailSubject());
        $this->settingManager->save('member.email.new_subscription_body', $data->getNewSubscriptionEmailBody());
        $this->settingManager->save('member.email.new_fee_coming_soon_email_subject', $data->getNewFeeComingSoonEmailSubject());
        $this->settingManager->save('member.email.new_fee_coming_soon_email_body', $data->getNewFeeComingSoonEmailBody());
        $this->settingManager->save('member.email.late_member_notification_email_subject', $data->getLateMemberNotificationEmailSubject());
        $this->settingManager->save('member.email.late_member_notification_email_body', $data->getLateMemberNotificationEmailBody());

        $this->settingManager->save('subscription.delay.late_payment_member_email_sending', $data->getLatePaymentMemberEmailSendingDelay());
        $this->settingManager->save('subscription.delay.new_fee_coming_soon_email_sending', $data->getNewFeeComingSoonEmailSendingDelay());

        $this->setForm();
    }
}