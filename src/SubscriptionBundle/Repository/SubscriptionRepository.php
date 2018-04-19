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
        return $this->getQb()
            ->select("s, ms")
            ->leftJoin("s.memberSubscription", "ms")
            ->where("(ms.id IS NULL OR ms.endDate >= :now )")->setParameter(":now", new \DateTime());
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