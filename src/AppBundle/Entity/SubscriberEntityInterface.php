<?php

namespace AppBundle\Entity;


interface SubscriberEntityInterface
{
    public function getId();

    public function addSubscription();
    public function getSubscriptions();
}