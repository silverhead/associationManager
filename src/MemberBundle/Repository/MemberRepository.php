<?php

namespace MemberBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\Repository\UserRepository;

class MemberRepository extends UserRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("m")
            ->select("m, msh, mshStatus, subscriptions, subscription" )
            ->leftJoin("m.status","msh")
            ->leftJoin("msh.status", "mshStatus")
            ->leftJoin("m.subscriptions", "subscriptions")
            ->leftJoin("subscriptions.subscription", "subscription")
            ;
//            ->addGroupBy("m")
//            ->where("status.startDate <= ".(new \DateTime())->format("Y-m-d")." AND status.endDate IS NULL")
//            ;
    }
    
    public function countMember(){
        return $this->createQueryBuilder("m")->select("COUNT(m)")->getQuery()->getSingleScalarResult();
    }
}