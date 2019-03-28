<?php

namespace SubscriptionBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class PaymentTypeRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("p");
    }

    public function countByCode(string $code): int
    {
        $qb = $this->createQueryBuilder("p")->select("COUNT(p)");
        $qb->orWhere("p.code = :code")->setParameter("code", $code);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}