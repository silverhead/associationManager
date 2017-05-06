<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Periodicity;
use AppBundle\Handler\ErrorHandlerTrait;
use AppBundle\Handler\ErrorHandlerInterface;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;

class PeriodicityManager implements EntityManagerInterface, ErrorHandlerInterface
{
    use ErrorHandlerTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator )
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
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

    public function save($periodicity)
    {
        try{
            $this->entityManager->persist($periodicity);
            $this->entityManager->flush();

            return true;
        }
        catch(\Exception $e){
            $this->addError($e->getMessage());

            return false;
        }
    }

    public function paginatedList($page = 1, $itemPerPage = 10, $pageParameterName = 'page')
    {
        $qb = $this->getRepository()->getQbPaginatedList();

        return $this->paginator->paginate($qb, $page, $itemPerPage, [
            'pageParameterName' => $pageParameterName,
            'anchor' => '#periodicities'
        ]);
    }


    public function getRepository()
    {
        return $this->entityManager->getRepository("AppBundle:Periodicity");
    }

    public function delete($periodicity)
    {
        try{
            $this->entityManager->remove($periodicity);
            $this->entityManager->flush();

            return true;
        }
        catch(\Exception $e){
            $this->addError($e->getMessage());

            return false;
        }
    }
}
