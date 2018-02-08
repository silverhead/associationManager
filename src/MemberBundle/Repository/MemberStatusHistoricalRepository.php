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

    public function getStatusListOfMember(Member $member, $orders = array("s.startDate" => "desc"), $limit = 5)
    {
        $qb = $this->createQueryBuilder("s")
            ->select("s, status")
            ->join("s.status", "status")
            ->where("s.member = :member")
            ->setParameter(":member", $member);

        foreach ($orders as $field => $order){
            $qb->addOrderBy($field, $order);
        }

        $qb->setMaxResults($limit);

        return $qb->getResult();
    }
}