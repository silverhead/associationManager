<?php

namespace UserBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    private $credentials = array();

    public function __construct($credentials){
        $this->credentials = $credentials;
    }


    protected function supports($attribute, $subject = null)
    {
        return in_array($attribute, $this->credentials);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $credentials = $token->getUser()->getGroup()->getCredentials();

        if(
            in_array($attribute, $credentials)
            ||
            in_array('ROLE_ADMIN', $token->getUser()->getRoles())
        ){
            return true;
        }

        return false;
    }

}