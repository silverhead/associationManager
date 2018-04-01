<?php

namespace MemberBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class MemberStatusRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        $qb = $this->createQueryBuilder("ms");
        $qb->select("ms, msh")
            ->leftJoin("ms.members", "msh")
           ->where($qb->expr()->isNull("msh.endDate"));
        return $qb;
    }
}