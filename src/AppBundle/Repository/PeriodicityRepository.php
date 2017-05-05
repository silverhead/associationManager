<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class PeriodicityRepository extends EntityRepository
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("p");
    }
}