<?php

namespace MemberBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\Repository\UserRepository;

class MemberRepository extends UserRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        $subquery = $this->_em->createQuery("
            SELECT MAX(sub2.id) 
            FROM MemberBundle:MemberSubscriptionHistorical sub2 
            WHERE sub2.member = m
            ")->getDQL();

        $qb = $this->createQueryBuilder("m");

        $qb->select("m, msh, mshStatus, subscriptions, subscription, fees ")
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
    
    public function countMember(bool $onlyActive = false){
        $qb = $this->createQueryBuilder("m")->select("COUNT(m)");

        if($onlyActive){
            $qb->where("m.active = 1");
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}