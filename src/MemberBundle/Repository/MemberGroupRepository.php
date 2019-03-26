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

    public function countByLabels(array $labels)
    {
        $qb = $this->createQueryBuilder("mg")->select("COUNT(mg)");

        foreach ($labels as $label) {
            $qb->orWhere("mg.label = :label")->setParameter("label", $label);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}