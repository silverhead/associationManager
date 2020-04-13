<?php

namespace AccountingBundle\Manager;

use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use AccountingBundle\Entity\Exercise;

class ExerciseManager implements PaginatorManagerInterface
{
    use EntityManagerTrait,
        PaginatorManagerTrait,
        ErrorHandlerTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function getNewEntity()
    {
        return new Exercise();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("AccountingBundle:Exercise");
    }

    public function saveExercise(Exercise $entity) {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        
        return true;
    }
    
    public function getExerciseList()
    {
        $exerciseRepo = $this->getRepository();  
        $exerciseList = $exerciseRepo->findAll();
        
        return $exerciseList;
    }
    
    public function getLastExercise() {
        $exerciseRepo = $this->getRepository();  
        $exercise = $exerciseRepo->findLast();
        
        return $exercise;
    }
}
