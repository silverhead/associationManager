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
    
    public function getEntriesForAccount($accountId, $dateDebut=null, $dateFin=null) {
        $accountableAccountRepo = $this->entityManager->getRepository("AccountingBundle:AccountableAccount");
        $dateDebut = $dateDebut != null ? $dateDebut : date("Y-m-d", 0);
        $dateFin = $dateFin != null ? $dateFin : date("Y-m-d"); 
        $accountableAccount = $accountableAccountRepo->findAll($accountId, $dateDebut, $dateFin);

        return count($accountableAccount) == 1 ? $accountableAccount[0] : null;
    }
    
    public function getEntriesForAccountAndDate($accountId, $date) {
        $accountableAccountRepo = $this->entityManager->getRepository("AccountingBundle:AccountableAccount");
        $accountableAccount = $accountableAccountRepo->findAll($accountId, $date);

        return count($accountableAccount) == 1 ? $accountableAccount[0] : null;
    }
    
    public function getEntryById($entryId) {
        $entryRepo = $this->entityManager->getRepository("AccountingBundle:Entry");
        return $entryRepo->findById($entryId);
    }
    
    public function getSoldeById($soldeId) {
        $soldeRepo = $this->entityManager->getRepository("AccountingBundle:Solde");
        return $soldeRepo->findById($soldeId);
    }
    
    /**
     * override
     */
    public function saveEntry(Entry $entity) {        
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return true;
    }
    
    public function saveSolde(Solde $entity) {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        
        return true;
    }

    public function getSoldesForAccount($accountId, $dateDebut=null, $dateFin=null) {
        $accountableAccountRepo = $this->entityManager->getRepository("AccountingBundle:AccountableAccount");
        $dateDebut = $dateDebut != null ? $dateDebut : date("Y-m-d", 0);
        $dateFin = $dateFin != null ? $dateFin : date("Y-m-d"); 
        $accountableAccount = $accountableAccountRepo->findAll($accountId, $dateDebut, $dateFin);

        return count($accountableAccount) == 1 ? $accountableAccount[0] : null;
    }

    public function getExerciseList($dateDebut, $dateFin)
    {
        $exerciseRepo = $this->entityManager->getRepository("AccountingBundle:Exercise");
        $dateDebut = $dateDebut != null ? $dateDebut : date("Y-m-d", 0);
        $dateFin = $dateFin != null ? $dateFin : date("Y-m-d");       
        $exerciseList = $exerciseRepo->findByPeriode($dateDebut, $dateFin);
        
        return $exerciseList;
    }
}
