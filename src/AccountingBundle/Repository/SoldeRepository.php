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
    
    public function findLastSoldeForAccountableAccount($accountableAccount) {
        $qb = $this->createQueryBuilder("s");
        $qb->setMaxResults(1);
        
        $qb->select("s")
            ->where("s.accountableAccount = :accountableAccount")
                ->setParameter("accountableAccount", $accountableAccount)
            ->orderBy("s.date", "DESC");
        
        return $qb->getQuery()->getSingleResult();
    }
}
