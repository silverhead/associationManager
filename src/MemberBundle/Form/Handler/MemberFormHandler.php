<?php

namespace MemberBundle\Form\Handler;

use MemberBundle\Form\Type\MemberFormType;
use MemberBundle\Form\Type\MemberProfileFormType;
use Symfony\Component\Security\Core\User\UserInterface;
use UserBundle\Form\Handler\UserFormHandler;

class MemberFormHandler extends UserFormHandler
{
    public function setForm(UserInterface $member = null, $profile = false)
    {
        $settingManager = $this->container->get('app.manager.setting');

        parent::setForm($member, $profile);

        $currentAvatar = '/images/avatars/user.png';

        $formType = MemberFormType::class;
        if($profile){
            $formType = MemberProfileFormType::class;
        }

        $this->form = $this->formFactory->create($formType, $member, [
            'currentAvatar' => $currentAvatar,
            'entity' => $this->entity,
            'gender' => $settingManager->getFormatedSettingValue('member.setting.gender'),
            'study' => $settingManager->getFormatedSettingValue('member.setting.study'),
            'expertise' => $settingManager->getFormatedSettingValue('member.setting.expertise')
        ]);
    }
}