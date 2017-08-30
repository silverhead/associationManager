<?php

namespace MemberBundle\Security;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MemberStatusVoter extends Voter
{
    private $credentials = array();

    public function __construct($credentials){
        $this->credentials = array_keys($credentials);
    }


    protected function supports($attribute, $subject = null)
    {
        return in_array($attribute, $this->credentials);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if(in_array($attribute, $this->credentials)){
            return true;//for test only todo to remove when the credential system setup will be finished
        }

        return false;
    }

}