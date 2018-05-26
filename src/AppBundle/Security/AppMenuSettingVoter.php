<?php

namespace AppBundle\Security;

use AppBundle\Event\MenuCredentialEvent;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AppMenuSettingVoter extends Voter
{
    protected $masterSettingCredentialMenu = "";
    protected $credentials = array();

    public function __construct(Container $container, string $masterSettingCredentialMenu){
        $this->masterSettingCredentialMenu = $masterSettingCredentialMenu;

        $menuCredentialEvent = new MenuCredentialEvent();
        $container->get('event_dispatcher')->dispatch(
            MenuCredentialEvent::EVENT_NAME,
            $menuCredentialEvent
        );

        $this->credentials = $menuCredentialEvent->getCredentialsList();
    }

    protected function supports($attribute, $subject = null)
    {
        return ($attribute == $this->masterSettingCredentialMenu);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $credentials = $token->getUser()->getGroup()->getCredentials();

        if(
            in_array('ROLE_ADMIN', $token->getUser()->getRoles())
            ||
            count(array_intersect($this->credentials, $credentials))  > 0  && $this->masterSettingCredentialMenu == $attribute)
        {
            return true;
        }

        return false;
    }
}