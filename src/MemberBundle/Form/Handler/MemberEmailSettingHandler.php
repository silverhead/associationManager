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
                $this->settingManager->getSetting('member.email.welcome_subject')->getValue()
            )
            ->setWelcomeEmailBody(
                nl2br($this->settingManager->getSetting('member.email.welcome_body')->getValue())
            )
            ->setNewSubscriptionEmailSubject(
                $this->settingManager->getSetting('member.email.new_subscription_subject')->getValue()
            )
            ->setNewSubscriptionEmailBody(
                nl2br($this->settingManager->getSetting('member.email.new_subscription_body')->getValue())
            )
            ->setNewFeeComingSoonEmailSubject(
                $this->settingManager->getSetting('member.email.new_fee_coming_soon_email_subject')->getValue()
            )
            ->setNewFeeComingSoonEmailBody(
                nl2br($this->settingManager->getSetting('member.email.new_fee_coming_soon_email_body')->getValue())
            )
            ->setLateMemberNotificationEmailSubject(
                $this->settingManager->getSetting('member.email.late_member_notification_email_subject')->getValue()
            )
            ->setLateMemberNotificationEmailBody(
                nl2br($this->settingManager->getSetting('member.email.late_member_notification_email_body')->getValue())
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
        $data = $this->form->getData();

        $this->settingManager->save('member.email.welcome_subject', $data->getGender());
        $this->settingManager->save('member.email.welcome_body', $data->getStudy());
        $this->settingManager->save('member.email.new_subscription_subject', $data->getExpertise());
        $this->settingManager->save('member.email.new_subscription_body', $data->getExpertise());
        $this->settingManager->save('member.email.new_fee_coming_soon_email_subject', $data->getExpertise());
        $this->settingManager->save('member.email.new_fee_coming_soon_email_body', $data->getExpertise());
        $this->settingManager->save('member.email.late_member_notification_email_subject', $data->getExpertise());
        $this->settingManager->save('member.email.late_member_notification_email_body', $data->getExpertise());

        $this->setForm();
    }
}