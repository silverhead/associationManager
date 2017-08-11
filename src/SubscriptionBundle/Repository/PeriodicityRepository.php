<?php

namespace SubscriptionBundle\Repository;


use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class PeriodicityRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("p");
    }
}