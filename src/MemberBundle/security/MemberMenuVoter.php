<?php

namespace MemberBundle\security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MemberMenuVoter extends Voter
{
    protected $credentials;

    protected $masterCredentialMenu;

    public function __construct($credentials, $masterCredentialMenu){
        $this->credentials = $credentials;

        $this->masterCredentialMenu = $masterCredentialMenu;
    }

    protected function supports($attribute, $subject = null)
    {
        return $attribute ==  $this->masterCredentialMenu;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $credentials = $token->getUser()->getGroup()->getCredentials();

        if( count(array_intersect($this->credentials, $credentials)) > 0 &&  $attribute == $this->masterCredentialMenu){
            return true;
        }

        return false;
    }
}