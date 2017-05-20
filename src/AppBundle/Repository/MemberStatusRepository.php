<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MemberStatusRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("ms");
    }

}