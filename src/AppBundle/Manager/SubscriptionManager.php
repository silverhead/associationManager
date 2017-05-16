<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Subscription;
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

    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager, PaginatorInterface $paginator )
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("AppBundle:Subscription");
    }

    public function getNewEntity()
    {
        return new Subscription();
    }


}