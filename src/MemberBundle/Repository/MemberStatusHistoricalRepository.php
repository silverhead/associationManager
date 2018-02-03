<?php

namespace MemberBundle\Repository;

use Doctrine\ORM\EntityRepository;
use MemberBundle\Entity\Member;

class MemberStatusHistoricalRepository extends EntityRepository
{
    public function getLastStatusOfMember(Member $member)
    {
        return $this->createQueryBuilder("s")
            ->select("s, status")
            ->join("s.status", "status")
            ->where("s.member = :member")
            ->setParameter(":member", $member)
            ->andWhere("s.endDate is null")
            ->getQuery()
            ->getFirstResult();
    }

    public function getStatusListOfMember(Member $member, $orders = array(array("s.startDate", "desc")), $limit = 5)
    {
        $qb = $this->createQueryBuilder("s")
            ->select("s, status")
            ->join("s.status", "status")
            ->where("s.member = :member")
            ->setParameter(":member", $member);

        foreach ($orders as $order){
            $qb->AddOrderBy($order[0], $order[1]);
        }

        $qb->setMaxResults($limit);

        return $qb->getMaxResults();
    }
}