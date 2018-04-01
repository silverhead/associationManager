<?php

namespace MemberBundle\Manager;

use AppBundle\Handler\ErrorHandlerTrait;
use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use MemberBundle\Entity\MemberSubscriptionFee;

class MemberSubscriptionFeeManager implements PaginatorManagerInterface
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

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function getNewEntity()
    {
        return new MemberSubscriptionFee();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("MemberBundle:MemberSubscriptionFee");
    }

    public function getTotalPaidFee(){
        return $this->getRepository()->getTotalFee(true);
    }
}