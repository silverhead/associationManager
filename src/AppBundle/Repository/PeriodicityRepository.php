<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class PeriodicityRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("p");
    }
}