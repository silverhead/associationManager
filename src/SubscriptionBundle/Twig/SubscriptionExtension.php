<?php

namespace SubscriptionBundle\Twig;

use SubscriptionBundle\Entity\Subscription;
use Doctrine\Common\Persistence\ObjectRepository;
use Twig_Environment;

class SubscriptionExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('countSubscriberMembers', array($this, 'countSubscriberMembersFilter'))
        ];
    }

    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function countSubscriberMembersFilter(Subscription $subscription)
    {
        return $this->repository->getCountSubscriberMembers($subscription);
    }

    public function getGlobals()
    {
        // TODO: Implement getGlobals() method.
    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function initRuntime(Twig_Environment $environment)
    {
        // TODO: Implement initRuntime() method.
    }
}