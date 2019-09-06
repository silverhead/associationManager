<?php

namespace AccountingBundle\Manager;

use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use AccountingBundle\Entity\Entry;

class EntryManager implements PaginatorManagerInterface
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
        return new Entry();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("AccountingBundle:Entry");
    }
    
    public function getEntriesByAccountForDashboard()
    {
        $accountableAccountRepo = $this->entityManager->getRepository("AccountingBundle:AccountableAccount");
        $accountableAccounts = $accountableAccountRepo->findAll();

        return $accountableAccounts;
    }
}