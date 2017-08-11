<?php

namespace SubscriptionBundle\Manager;

use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use SubscriptionBundle\Entity\Subscription;
use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;

class SubscriptionManager implements PaginatorManagerInterface
{
    use EntityManagerTrait,
        PaginatorManagerTrait,
        ErrorHandlerTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator )
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("SubscriptionBundle:Subscription");
    }

    public function getNewEntity()
    {
        return new Subscription();
    }

    public function getCountAllSubscriberMembers()
    {
       return $this->getRepository()->getCountAllSubscriberMembers();
    }

}