<?php

namespace SubscriptionBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use SubscriptionBundle\Entity\Subscription;
use Doctrine\ORM\EntityRepository;

class SubscriptionRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQb()
    {
        return $this->createQueryBuilder("s");
    }

    public function getQbPaginatedList()
    {
        $subquery = $this->_em->createQuery("
            SELECT COUNT(sub2.id) FROM MemberBundle:MemberSubscriptionHistorical sub2 
            WHERE sub2.subscription = s
            GROUP BY sub2.subscription
            ")
//            ->setParameter(":now", new \DateTime()) AND sub2.endDate >= :now
            ->getDQL();

        $qb = $this->getQb()
            ->select("s")
            ->addSelect("(" . $subquery .") AS nbMembres ");

        dump($qb);

        return $qb;
    }

    public function getCountSubscriberMembers(Subscription $subscription)
    {
        return $this->getQb()
            ->select("COUNT(ms)")
            ->distinct()
            ->join("s.memberSubscription", "ms")
            ->where("s = :sub")
                ->setParameter(':sub', $subscription)
            ->andWhere(":now BETWEEN ms.startDate AND ms.endDate")
                ->setParameter(":now", new \DateTime())
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getCountAllSubscriberMembers()
    {
        return $this->getQb()
            ->select("COUNT(ms)")
            ->distinct()
            ->join("s.memberSubscription", "ms")
            ->andWhere(":now BETWEEN ms.startDate AND ms.endDate")
                ->setParameter(":now", new \DateTime())
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}