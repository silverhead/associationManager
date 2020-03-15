<?php

namespace AccountingBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityRepository;

class SoldeRepository extends EntityRepository implements PaginatorRepositoryInterface {
    public function getQbPaginatedList() {
        $qb = $this->createQueryBuilder("s");

        $qb->select("m ")
            ->leftJoin("m.statusHistorical","msh", Join::WITH, $qb->expr()->isNull("msh.endDate"))
            ->leftJoin("m.fees", "fees")
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->isNull("subscriptions.id")
                )->add($qb->expr()->in("subscriptions.id", $subquery ))
            )
            ;
        return $qb;
    }
    
    public function countEntry(bool $onlyActive = false) {
        $qb = $this->createQueryBuilder("s")->select("COUNT(s)");

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function findAll() {
        return $this->createQueryBuilder("s")
            ->getQuery()
            ->getResult();
    }
    
    public function findByAccountableAccount($accountableAccount, $dateDebut, $dateFin) {
        $qb = $this->createQueryBuilder("s");
        
        $qb->select("s")
            ->where("s.accountableAccount = :accountableAccount")
            ->andWhere("s.date between :dateDebut and :dateFin")
                ->setParameter("accountableAccount", $accountableAccount)
                ->setParameter("dateDebut", $dateDebut)
                ->setParameter("dateFin", $dateFin)
            ->orderBy("s.date", "DESC");
        
        return $qb->getQuery()->getResult();
    }
    
    public function findLastSoldeForAccountableAccount($accountableAccount) {
        $qb = $this->createQueryBuilder("s");
        $qb->setMaxResults(1);
        
        $qb->select("s")
            ->where("s.accountableAccount = :accountableAccount")
                ->setParameter("accountableAccount", $accountableAccount)
            ->orderBy("s.date", "DESC");
        
        return $qb->getQuery()->getSingleResult();
    }
    
    public function findOneByDateAndAccountableAccount($date, $accountableAccount) {
        /*
        $qb = $ccccc
        
        $qb->select("s")
            ->where("s.accountableAccount = :accountableAccount")
            ->where("s.date = :date")
                ->setParameter("accountableAccount", $accountableAccount)
                ->setParameter("date", $date)
            ->orderBy("s.date", "DESC");
        
        return $qb->getQuery()->getResult();
        */
        return $this->findOneBy(array(
            'date' => $date,
            'accountableAccount' => $accountableAccount
        ));
    }
    
    public function findById($soldeId)
    {
        //return $this->findOneBy(array('id' => $soldeId));
        $qb = $this->createQueryBuilder("s");
        $qb->select("s")
            ->where("s.id = :soldeId")
                ->setParameter("soldeId", $soldeId);
        return $qb
            ->getQuery()
            ->getResult();
    }
}
