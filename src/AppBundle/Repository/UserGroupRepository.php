<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserGroupRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("g");
    }

}