<?php

namespace MemberBundle\Security;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MemberStatusVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // TODO: Implement supports() method.
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // TODO: Implement voteOnAttribute() method.
    }

}