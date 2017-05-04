<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Periodicity;
use Doctrine\ORM\EntityManager;

class PeriodicityManager
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find($id = null)
    {
        if(null === $id){
            return new Periodicity();
        }

        $periodicity = $this->entityManager->getRepository("AppBundle:Periodicity")->find($id);

        if(null === $periodicity){
            return new Periodicity();
        }

        return $periodicity;
    }

    public function findALl()
    {
        return $this->entityManager->getRepository("AppBundle:Periodicity")->findAll();
    }

    public function save(Periodicity $periodicity)
    {
        try{
            $this->entityManager->persist($periodicity);
            $this->entityManager->flush();

            return true;
        }
        catch(\Exception $e){
            $this->errors[] = $e->getMessage();

            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}