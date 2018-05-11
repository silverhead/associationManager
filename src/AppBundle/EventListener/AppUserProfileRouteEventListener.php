<?php

namespace AppBundle\EventListener;

use AppBundle\Event\UserProfileEvent;

class AppUserProfileRouteEventListener
{
    private $profileRoute;

    private $key;

    public function __construct($key, $profileRoute)
    {
        $this->profileRoute = $profileRoute;
        $this->key = $key;
    }

    public function onProvideProfileRoute(UserProfileEvent $event)
    {
        $event->addProfileRoute($this->key, $this->profileRoute);
    }
}