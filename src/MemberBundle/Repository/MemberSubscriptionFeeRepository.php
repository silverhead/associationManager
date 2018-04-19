<?php

namespace MemberBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class MemberSubscriptionFeeRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        $qb = $this->createQueryBuilder("memberSubscriptionFee")
                ->select("memberSubscriptionFee, subHist, sub, mber")
                ->leftJoin("memberSubscriptionFee.subscription", "subHist")
                ->leftJoin("subHist.subscription", "sub")
                ->leftJoin("memberSubscriptionFee.member", "mber")
        ;

        return $qb;
    }

    public function getTotalFee(bool $paid = true):? float
    {
        $qb = $this->createQueryBuilder("msf")
            ->select("SUM(msf.cost) as totalFee");

        if($paid){
            $qb->where("msf.paid = 1");
        }

        return $qb->getQuery() ->getSingleScalarResult();
    }

    public function getAllLatePaymentMemberId(array $memberIdList)
    {
        $qb = $this->createQueryBuilder("msf")->select("msf.member");

        $qb->where("msf.startDate < :today")
            ->setParameter(":today", new \DateTime());

        if(!empty($memberIdList)){
            $qb->andWhere("msf.member IN (:memberList)")
                ->setParameter("memberList", $memberIdList);
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function getAllSoonFeeNewPaymentMemberId(DateTime $startPeriod, DateTime $endPeriod, array $memberIdList)
    {
        $qb = $this->createQueryBuilder("msf")->select("msf.member");

        $qb->where("msf.startDate between :startPeriod and :endPeriod")
            ->setParameter(":startPeriod", $startPeriod)
            ->setParameter(":endPeriod", $endPeriod);

        if(!empty($memberIdList)){
            $qb->andWhere("msf.member IN (:memberList)")
                ->setParameter("memberList", $memberIdList);
        }

        return $qb->getQuery()->getArrayResult();
    }
}