<?php

namespace MemberBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class MemberStatusRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("ms");
    }

}