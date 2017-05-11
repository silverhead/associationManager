<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SubscriptionRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("s");
    }

}