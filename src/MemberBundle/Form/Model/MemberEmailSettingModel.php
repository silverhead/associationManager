<?php

namespace MemberBundle\Form\Model;


class MemberEmailSettingModel
{
    /**
     * @var string
     */
    private $welcomeEmailSubject;

    /**
     * @var string
     */
    private $welcomeEmailBody;

    /**
     * @var string
     */
    private $newSubscriptionEmailSubject;

    /**
     * @var string
     */
    private $newSubscriptionEmailBody;

    /**
     * @var string
     */
    private $newFeeComingSoonEmailSubject;

    /**
     * @var string
     */
    private $newFeeComingSoonEmailBody;

    /**
     * @var string
     */
    private $lateMemberNotificationEmailSubject;

    /**
     * @var string
     */
    private $lateMemberNotificationEmailBody;

    /**
     * @return string
     */
    public function getWelcomeEmailSubject()
    {
        return $this->welcomeEmailSubject;
    }

    /**
     * @param string $welcomeEmailSubject
     * @return MemberEmailSettingModel
     */
    public function setWelcomeEmailSubject($welcomeEmailSubject)
    {
        $this->welcomeEmailSubject = $welcomeEmailSubject;

        return $this;
    }

    /**
     * @return string
     */
    public function getWelcomeEmailBody()
    {
        return $this->welcomeEmailBody;
    }

    /**
     * @param string $welcomeEmailBody
     * @return MemberEmailSettingModel
     */
    public function setWelcomeEmailBody($welcomeEmailBody)
    {
        $this->welcomeEmailBody = $welcomeEmailBody;

        return $this;
    }

    /**
     * @return string
     */
    public function getNewSubscriptionEmailSubject()
    {
        return $this->newSubscriptionEmailSubject;
    }

    /**
     * @param string $newSubscriptionEmailSubject
     * @return MemberEmailSettingModel
     */
    public function setNewSubscriptionEmailSubject($newSubscriptionEmailSubject)
    {
        $this->newSubscriptionEmailSubject = $newSubscriptionEmailSubject;

        return $this;
    }

    /**
     * @return string
     */
    public function getNewSubscriptionEmailBody()
    {
        return $this->newSubscriptionEmailBody;
    }

    /**
     * @param string $newSubscriptionEmailBody
     * @return MemberEmailSettingModel
     */
    public function setNewSubscriptionEmailBody($newSubscriptionEmailBody)
    {
        $this->newSubscriptionEmailBody = $newSubscriptionEmailBody;

        return $this;
    }

    /**
     * @return string
     */
    public function getNewFeeComingSoonEmailSubject()
    {
        return $this->newFeeComingSoonEmailSubject;
    }

    /**
     * @param string $newFeeComingSoonEmailSubject
     * @return MemberEmailSettingModel
     */
    public function setNewFeeComingSoonEmailSubject($newFeeComingSoonEmailSubject)
    {
        $this->newFeeComingSoonEmailSubject = $newFeeComingSoonEmailSubject;

        return $this;
    }

    /**
     * @return string
     */
    public function getNewFeeComingSoonEmailBody()
    {
        return $this->newFeeComingSoonEmailBody;
    }

    /**
     * @param string $newFeeComingSoonEmailBody
     * @return MemberEmailSettingModel
     */
    public function setNewFeeComingSoonEmailBody($newFeeComingSoonEmailBody)
    {
        $this->newFeeComingSoonEmailBody = $newFeeComingSoonEmailBody;

        return $this;
    }

    /**
     * @return string
     */
    public function getLateMemberNotificationEmailSubject()
    {
        return $this->lateMemberNotificationEmailSubject;
    }

    /**
     * @param string $lateMemberNotificationEmailSubject
     * @return MemberEmailSettingModel
     */
    public function setLateMemberNotificationEmailSubject($lateMemberNotificationEmailSubject)
    {
        $this->lateMemberNotificationEmailSubject = $lateMemberNotificationEmailSubject;

        return $this;
    }

    /**
     * @return string
     */
    public function getLateMemberNotificationEmailBody()
    {
        return $this->lateMemberNotificationEmailBody;
    }

    /**
     * @param string $lateMemberNotificationEmailBody
     * @return MemberEmailSettingModel
     */
    public function setLateMemberNotificationEmailBody($lateMemberNotificationEmailBody)
    {
        $this->lateMemberNotificationEmailBody = $lateMemberNotificationEmailBody;

        return $this;
    }
}