<?php

namespace SubscriptionBundle\Security;

use AppBundle\Security\AppVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SubscriptionMenuVoter extends AppVoter
{
    protected $credentials = array();

    private $masterCredentialMenu;

    public function __construct($credentials, $masterCredentialMenu){
        $this->credentials = $credentials;

        $this->masterCredentialMenu = $masterCredentialMenu;
    }

    protected function supports($attribute, $subject = null)
    {
        return $attribute == $this->masterCredentialMenu;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $credentials = $token->getUser()->getGroup()->getCredentials();

        if( count(array_intersect($this->credentials, $credentials)) > 0 && $attribute == $this->masterCredentialMenu){
            return true;
        }

        return false;
    }

}