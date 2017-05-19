<?php

namespace AppBundle\Manager;

use AppBundle\Entity\SubscriptionPaymentPeriodicity;
use AppBundle\Handler\ErrorHandlerTrait;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManager;

class PeriodicityManager implements PaginatorManagerInterface
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

    public function getNewEntity()
    {
        return new SubscriptionPaymentPeriodicity();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("AppBundle:SubscriptionPaymentPeriodicity");
    }
}
