<?php

namespace AccountingBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityRepository;

class ExerciseRepository extends EntityRepository implements PaginatorRepositoryInterface {
    public function getQbPaginatedList() {
        $qb = $this->createQueryBuilder("s");

        $qb->select("m ")
            ->leftJoin("m.statusHistorical","msh", Join::WITH, $qb->expr()->isNull("msh.endDate"))
            ->leftJoin("m.fees", "fees")
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->isNull("subscriptions.id")
                )->add($qb->expr()->in("subscriptions.id", $subquery ))
            )
            ;
        return $qb;
    }
    
    public function countEntry(bool $onlyActive = false) {
        $qb = $this->createQueryBuilder("s")->select("COUNT(s)");

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function findAll() {
        return $this->createQueryBuilder("e")
            ->getQuery()
            ->getResult();
    }
    
    public function findByPeriode($dateDebut, $dateFin) {
        $qb = $this->createQueryBuilder("e");
        
        $qb->select("e")
            ->Where("e.dateStart between :dateDebut and :dateFin")
                ->OrWhere("e.dateEnd between :dateDebut and :dateFin")
                ->setParameter("dateDebut", $dateDebut)
                ->setParameter("dateFin", $dateFin)
            ->orderBy("e.dateStart", "DESC");
        
        return $qb->getQuery()->getResult();
    }
    
    public function findLastExercise() {
        $qb = $this->createQueryBuilder("e");
        $qb->setMaxResults(1);
        
        $qb->select("e")
            ->orderBy("e.date", "DESC");
        
        return $qb->getQuery()->getSingleResult();
    }
    
    public function findOneByDate($date) {
        return $this->findOneBy(array(
            'date' => $date
        ));
    }
}
