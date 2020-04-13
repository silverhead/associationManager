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
    
    public function countEntry(bool $onlyActive = false)
    {
        $qb = $this->createQueryBuilder("j")->select("COUNT(j)");

        if ($onlyActive) {
            $qb->where("j.active = 1");
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function findAll()
    {
        return $this->createQueryBuilder("e")
            ->getQuery()
            ->getResult();
    }
    
    public function findEntriesForAccountId($accountId, $dateStart, $dateEnd) {
        $qb = $this->createQueryBuilder("e")
                ->select("e")
                ->where("e.accountableAccount = :accountId")
                    ->andWhere("e.accountingDate between :dateStart and :dateEnd")
                        ->setParameter("accountId", $accountId)
                        ->setParameter("dateStart", $dateStart)
                        ->setParameter("dateEnd", $dateEnd)
                ->addOrderBy('e.accountingDate', 'DESC')
                ->addOrderBy('e.reference', 'ASC')
                ->addOrderBy('e.id', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }
    
    public function findById($entryId)
    {
        /*
        $qb = $this->createQueryBuilder("e");
        $qb->select("e")
            //->innerJoin('e.accountableAccount', 'a')
            ->where("e.id = :id")
                ->setParameter("id", $entryId);
        
        return $qb->getQuery()->getSingleResult();
        */
        return $this->findOneBy(array('id' => $entryId));
    }
}
