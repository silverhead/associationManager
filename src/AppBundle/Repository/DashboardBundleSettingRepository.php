<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DashboardBundleSettingRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("dbs");
    }
}