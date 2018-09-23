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

    public function getLatePaymentFeeMemberList($limit = 10, $orders = array())
    {
        $qb = $this->createQueryBuilder("msf")
            ->select('msf, SUM(msf.cost) as cost, m')
            ->join("msf.member", "m")
        ;

        $qb->where("msf.startDate < :today")
            ->andWhere("msf.paid = 0")
            ->setParameter(":today", new \DateTime());

        if(count($orders) > 0){
            foreach ($orders as $sort => $order){
                $qb->addOrderBy($sort, $order);
            }
        }

        $qb->groupBy("m");

        $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function getLatePaymentFeeMemberListForSendingMail($delaySending)
    {


        $qb = $this->createQueryBuilder("msf")
            ->select('
            m.id,
            m.gender,
            m.lastName,
            m.firstName,
            m.email,
            s.label as subscriptionLabel,                    
            msf.startDate,
            msf.endDate,
            SUM(msf.cost) as cost'
            )
            ->join("msf.member", "m")
            ->join("msf.subscription", "msh")
            ->join("msh.subscription", "s")
        ;

        $qb->where("msf.startDate < :today")
            ->andWhere("msf.paid = 0")
            ->andWhere("m.active = 1")
            ->setParameter(":today", new \DateTime());

        $dateStarAuthorized = new \DateTime();
        $dateStarAuthorized->sub(new \DateInterval("P". $delaySending . "D"));

        $qb->andWhere("m.lastSendingLatePaymentEmailDate < :dateStarAuthorized")
            ->setParameter(":dateStarAuthorized", $dateStarAuthorized);

        $qb->groupBy("m");

        return $qb->getQuery()->getArrayResult();
    }

    public function getLatePaymentFeeByMemberIdList(array $memberIdList)
    {
        $qb = $this->createQueryBuilder("msf")
            ->select('m.id')
            ->join("msf.member", "m")
        ;

        $qb->where("msf.startDate < :today")
            ->andWhere("msf.paid = 0")
            ->setParameter(":today", new \DateTime());

        if(!empty($memberIdList)){
            $qb->andWhere("msf.member IN (:memberList)")
                ->setParameter("memberList", $memberIdList);
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function getSoonFeeNewPaymentMemberList(\DateTime $startPeriod, \DateTime $endPeriod, $limit = 10, $orders = array())
    {
        $qb = $this->createQueryBuilder("msf")
            ->select('msf, m')
            ->join("msf.member", "m")
        ;

        $qb->where("msf.endDate between :startPeriod and :endPeriod")
            ->setParameter(":startPeriod", $startPeriod)
            ->setParameter(":endPeriod", $endPeriod);

        if(count($orders) > 0){
            foreach ($orders as $sort => $order){
                $qb->addOrderBy($sort, $order);
            }
        }

        $qb->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param \DateTime $startPeriod
     * @param \DateTime $endPeriod
     * @param $delaySending
     * @return array
     * @throws \Exception
     */
    public function getSoonFeeNewPaymentMemberListForSendingEmail(\DateTime $startPeriod, \DateTime $endPeriod, $delaySending)
    {
        $qb = $this->createQueryBuilder("msf")
            ->select('
            m.id,
            m.gender,
            m.lastName,
            m.firstName,
            m.email,
            s.label as subscriptionLabel,                    
            msf.startDate,
            msf.endDate,
            SUM(msf.cost) as cost'
            )
            ->join("msf.member", "m")
            ->join("msf.subscription", "msh")
            ->join("msh.subscription", "s")
        ;

        $qb->where("msf.endDate between :startPeriod and :endPeriod")
            ->setParameter(":startPeriod", $startPeriod)
            ->setParameter(":endPeriod", $endPeriod);

        $dateStarAuthorized = new \DateTime();
        $dateStarAuthorized->sub(new \DateInterval("P". $delaySending . "D"));

        $qb->andWhere("m.lastSendingComingSoonFeeEmailDate < :dateStarAuthorized")
            ->setParameter(":dateStarAuthorized", $dateStarAuthorized);

        $qb->groupBy("m");

        return $qb->getQuery()->getArrayResult();
    }

    public function getLatePaymentFeeMemberIdList()
    {
        $qb = $this->createQueryBuilder("msf")
            ->select("m.id")
            ->join("msf.member", "m")
        ;

        $qb->where("msf.startDate < :today")
            ->andWhere("msf.paid = 0")
            ->setParameter(":today", new \DateTime());


        //$qb->groupBy("m.id");

        $result = $qb->getQuery()->getArrayResult();

        $array = array();

        foreach($result as $item){
            $array[] = $item['id'];
        }

        return $array;
    }

    public function getSoonFeeNewPaymentMemberIdList(\DateTime $startPeriod, \DateTime $endPeriod, $limit = 10, $orders = array())
    {
        $qb = $this->createQueryBuilder("msf")
            ->select("m.id")
            ->join('msf.member', 'm')
        ;

        $qb->where("msf.endDate between :startPeriod and :endPeriod")
            ->setParameter(":startPeriod", $startPeriod)
            ->setParameter(":endPeriod", $endPeriod);

        if(count($orders) > 0){
            foreach ($orders as $sort => $order){
                $qb->addOrderBy($sort, $order);
            }
        }

        $qb->setMaxResults($limit);

        $result = $qb->getQuery()->getArrayResult();

        $array = array();

        foreach($result as $item){
            $array[] = $item['id'];
        }

        return $array;
    }

    public function getSoonFeeNewPaymentListByMemberIdList(\DateTime $startPeriod, \DateTime $endPeriod, array $memberIdList)
    {
        $qb = $this->createQueryBuilder("msf")
                ->select("m.id as id")
                ->join('msf.member', 'm')
        ;

        $qb->where("msf.endDate between :startPeriod and :endPeriod")
            ->setParameter(":startPeriod", $startPeriod)
            ->setParameter(":endPeriod", $endPeriod);

        if(!empty($memberIdList)){
            $qb->andWhere("msf.member IN (:memberList)")
                ->setParameter("memberList", $memberIdList);
        }

        return $qb->getQuery()->getArrayResult();
    }
}