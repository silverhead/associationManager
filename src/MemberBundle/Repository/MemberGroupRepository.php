<?php

namespace MemberBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class MemberGroupRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("mg");
    }

    public function countByLabels(array $labels): int
    {
        $qb = $this->createQueryBuilder("mg")->select("COUNT(mg)");

        foreach ($labels as $i => $label) {
            $qb->orWhere("TRIM(mg.label) = TRIM(:label".$i.")")->setParameter("label".$i, $label);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}