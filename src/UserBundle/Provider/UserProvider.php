<?php

namespace UserBundle\Provider;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        // TODO: Implement loadUserByUsername() method.
    }

    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    public function supportsClass($class)
    {
        // TODO: Implement supportsClass() method.
    }

}