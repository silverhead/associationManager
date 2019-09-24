<?php

namespace AccountingBundle\Manager;

use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use AccountingBundle\Entity\Entry;
use AccountingBundle\Entity\Solde;

class AccountingManager implements PaginatorManagerInterface
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
        return new Entry();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("AccountingBundle:");
    }
    
    public function getEntriesByAccountForSynthesis()
    {
        $accountableAccountRepo = $this->entityManager->getRepository("AccountingBundle:AccountableAccount");
        $accountableAccounts = $accountableAccountRepo->findAll();

        return $accountableAccounts;
    }
    
    public function getEntriesForAccount($accountId) {
        $accountableAccountRepo = $this->entityManager->getRepository("AccountingBundle:AccountableAccount");
        $accountableAccount = $accountableAccountRepo->findAll($accountId);

        return count($accountableAccount) == 1 ? $accountableAccount[0] : null;
    }
    
    public function getEntryById($entryId) {
        $entryRepo = $this->entityManager->getRepository("AccountingBundle:Entry");
        return $entryRepo->findById($entryId);
    }
    
    /**
     * override
     */
    public function saveEntryAndupdateSolde(Entry $entity) {        
        $soldeRepo = $this->entityManager->getRepository("AccountingBundle:Solde");
        $lastSolde = $soldeRepo->findLastSoldeForAccountableAccount($entity->getAccountableAccount());
        $dateNewSolde = $entity->getIsPrev() ? $entity->getValueDate() : $entity->getAccountingDate();

        $newSolde = new Solde();
        $newSolde->setDate($dateNewSolde);
        $newSolde->setAmount($lastSolde->getAmount() + $entity->getAmount());
        $newSolde->setIsPrev($entity->getIsPrev());
        $newSolde->setUpdatedAt(new \DateTime());
        $newSolde->setAccountableAccount($entity->getAccountableAccount());
        
        $this->entityManager->persist($entity);
        $this->entityManager->persist($newSolde);
        $this->entityManager->flush();

        return true;
    }
}