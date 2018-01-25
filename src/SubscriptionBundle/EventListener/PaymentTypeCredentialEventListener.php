<?php

namespace SubscriptionBundle\EventListener;

use AppBundle\Event\CredentialEvent;

class PaymentTypeCredentialEventListener
{
    private $credentials;

    private $name;

    public function __construct($credentials, $name)
    {
        $this->credentials = $credentials;
        $this->name = $name;
    }

    public function onProvideCredential(CredentialEvent $event)
    {
        $event->addCredentials($this->name, $this->credentials);
    }
}