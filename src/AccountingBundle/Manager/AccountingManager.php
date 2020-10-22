<?php

namespace AccountingBundle\Manager;

use AccountingBundle\Entity\AccountableAccount;
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
    
    private $dateStart;
    private $dateEnd;

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
        return new AccountableAccount();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("AccountingBundle:AccountableAccount");
    }
    
    public function getAccountableAccount($accountId)
    {
        return $this->getRepository()->find($accountId);
    }
    
    public function getEntriesByAccountForSynthesis($dateStart=null, $dateEnd=null)
    {
        $dateStart = $this->getDateStart($dateStart);
        $dateEnd = $this->getDateEnd($dateEnd); 
        
        $accountableAccountRepo = $this->getRepository();
        $accountableAccounts = $accountableAccountRepo->findAllAccount($dateStart, $dateEnd);

        return $accountableAccounts;
    }
    
    public function getAccountWithEntriesByAccountId($accountId, $dateDebut=null, $dateFin=null) {
        $entryRepo = $this->entityManager->getRepository("AccountingBundle:Entry");
        $accountableAccountRepo = $this->getRepository();
        $dateDebut = $this->getDateStart($dateDebut);
        $dateFin = $this->getDateEnd($dateFin);     
        
        $accountableAccount = $accountableAccountRepo->findAccountWithEntries($accountId, $dateDebut, $dateFin);
        //var_dump($accountableAccount);die;
        if (count($accountableAccount) == 1) {
            $accountableAccount = $accountableAccount[0];
            $entries = $entryRepo->findEntriesForAccountId($accountableAccount->getId(), $dateDebut, $dateFin);
            $accountableAccount->populateEntries($entries);
            return $accountableAccount;
        }
        return null;
    }
    
    public function getEntriesForAccountAndDate($accountId, $date) {
        $accountableAccountRepo = $this->getRepository();
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
    public function saveAccountableAccount(AccountableAccount $entity) {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
    
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
        $accountableAccountRepo = $this->getRepository();
        $dateDebut = $this->getDateStart($dateDebut);
        $dateFin = $this->getDateEnd($dateFin);
        $accountableAccount = $accountableAccountRepo->findAll($accountId, $dateDebut, $dateFin);

        //var_dump($accountableAccount);exit();
        
        return count($accountableAccount) == 1 ? $accountableAccount[0] : null;
    }
    
    private function getDateStart($dateStart) {
        $dateToReturn = $dateStart;
        if ($dateStart == null) {
            if ($this->dateStart == null) {
                $exerciseRepo = $this->entityManager->getRepository("AccountingBundle:Exercise");
                $lastExercise = $exerciseRepo->findLast();
                if ($lastExercise == null) {
                    $this->dateStart = date("Y-m-d", 0);
                } else {
                    $this->dateStart = $lastExercise->getDateStart();
                }
            }
            $dateToReturn = $this->dateStart;
        }
        return $dateToReturn;
    }
    
    private function getDateEnd($dateEnd) {
        $dateToReturn = $dateEnd;
        if ($dateEnd == null) {
            if ($this->dateEnd == null) {
                $exerciseRepo = $this->entityManager->getRepository("AccountingBundle:Exercise");
                $lastExercise = $exerciseRepo->findLast();
                if ($lastExercise == null) {
                    $this->dateEnd = date("Y-m-d");
                } else {
                    $this->dateEnd = $lastExercise->getDateEnd();
                }
            }
            $dateToReturn = $this->dateEnd;
        }
        return $dateToReturn;
    }
}
