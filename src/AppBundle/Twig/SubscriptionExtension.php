<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Subscription;
use Doctrine\Common\Persistence\ObjectRepository;

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
}