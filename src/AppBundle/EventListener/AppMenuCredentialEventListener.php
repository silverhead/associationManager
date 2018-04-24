<?php

namespace AppBundle\EventListener;

use AppBundle\Event\MenuCredentialEvent;

class AppMenuCredentialEventListener
{
    private $credentials;

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    public function onProvideCredential(MenuCredentialEvent $event)
    {
        $event->addCredentials($this->credentials);
    }
}