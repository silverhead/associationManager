<?php

namespace AccountingBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityRepository;
//use Symfony\Component\Validator\Constraints\DateTime;

class AccountableAccountRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        $subquery = $this->_em->createQuery("
            SELECT MAX(id) 
            FROM AccountingBundle:AccountableAccount
            WHERE accountableaccount = aa
        ")->getDQL();

        $qb = $this->createQueryBuilder("aa");

        $qb->select("m ")
            ->leftJoin("m.statusHistorical","msh", Join::WITH, $qb->expr()->isNull("msh.endDate"))
            ->leftJoin("msh.status", "mshStatus" )
            ->leftJoin("m.subscriptions", "subscriptions")
            ->leftJoin("subscriptions.subscription", "subscription")
            ->leftJoin("m.fees", "fees")
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->isNull("subscriptions.id")
                )->add($qb->expr()->in("subscriptions.id", $subquery ))
            )
            ;
        return $qb;
    }
    
    public function countAccountableAccount(bool $onlyActive = false) {
        $qb = $this->createQueryBuilder("j")->select("COUNT(j)");

        if ($onlyActive) {
            $qb->where("j.active = 1");
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function findAll($id = null) {
        if ($id != null) {
            $qb = $this->createQueryBuilder('a')
                ->select('a, e')
                //->from('\AccountingBundle\Entity\AccountableAccount', 'a')
                ->innerJoin('a.entries', 'e')
                //->innerJoin('a.soldes', 's')
                ->where("a.id = :id")
                ->setParameter("id", $id)
                ->addOrderBy('a.label', 'ASC')
                ->addOrderBy('e.accountingDate', 'DESC');
        } else {
            $qb = $this->createQueryBuilder('a')
                ->select('a, e')
                //->from('\AccountingBundle\Entity\AccountableAccount', 'a')
                ->innerJoin('a.entries', 'e')
                ->addOrderBy('a.label', 'ASC')
                ->addOrderBy('e.accountingDate', 'DESC');
        }
        
        return $qb->getQuery()
            ->getResult();
    }
    
    public function findSoldes() {
        $qb = $this->createQueryBuilder('a')
            ->select('a, s')
            ->innerJoin('a.soldes', 's')
            ->orderBy('s.updatedAt', 'DESC');
        
        return $qb->getQuery()
            ->getResult();
    }
}