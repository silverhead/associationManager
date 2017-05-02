<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 02/05/2017
 * Time: 21:58
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Periodicity;
use Doctrine\ORM\EntityManager;

class PeriodicityManager
{
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

    public function save(Periodicity $periodicity)
    {
        $this->entityManager->persist($periodicity);
        $this->entityManager->flush();
    }
}