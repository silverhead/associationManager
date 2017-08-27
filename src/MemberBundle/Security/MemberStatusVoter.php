<?php

namespace MemberBundle\Security;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MemberStatusVoter extends Voter
{
    const MEMBER_STATUS_VIEW    = 'MEMBER_STATUS_VIEW';
    const MEMBER_STATUS_CREATE  = 'MEMBER_STATUS_CREATE';
    const MEMBER_STATUS_EDIT    = 'MEMBER_STATUS_EDIT';
    const MEMBER_STATUS_DELETE  = 'MEMBER_STATUS_DELETE';


    protected function supports($attribute, $subject = null)
    {
        return in_array($attribute, array(self::MEMBER_STATUS_VIEW, self::MEMBER_STATUS_CREATE, self::MEMBER_STATUS_EDIT, self::MEMBER_STATUS_DELETE));
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        switch ($attribute){
            case self::MEMBER_STATUS_EDIT:
                return false;
                break;
            default:
                return false;
                break;
        }

    }

}