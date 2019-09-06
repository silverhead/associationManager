<?php

namespace AccountingBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityRepository;
//use Symfony\Component\Validator\Constraints\DateTime;

class EntryRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        $subquery = $this->_em->createQuery("
            SELECT MAX(id) 
            FROM AccountingBundle:Entry  
            WHERE accountableaccount = aa
        ")->getDQL();

        $qb = $this->createQueryBuilder("aa");

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
        $qb = $this->createQueryBuilder("j")->select("COUNT(j)");

        if ($onlyActive) {
            $qb->where("j.active = 1");
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function findAll() {
        return $this->createQueryBuilder("e")
            ->getQuery()
            ->getResult();
    }
    
}
