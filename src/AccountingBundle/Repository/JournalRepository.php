<?php

namespace AccountingBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class JournalRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("j");
        return $qb;
    }

    public function findAll()
    {
        return $this->createQueryBuilder("j")
            ->getQuery()
            ->getResult();
    }
    
    public function findById(int $id)
    {
        return $this->findOneBy(array('id' => $id));
    }
}
