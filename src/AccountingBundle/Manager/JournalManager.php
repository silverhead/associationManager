<?php

namespace AccountingBundle\Manager;

use AccountingBundle\Entity\Journal;
use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use AccountingBundle\Entity\Entry;
use AccountingBundle\Entity\Solde;

class JournalManager implements PaginatorManagerInterface
{
    use EntityManagerTrait,
        PaginatorManagerTrait,
        ErrorHandlerTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;
    
    private $dateStart;
    private $dateEnd;

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
        return new Journal();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("AccountingBundle:Journal");
    }
}
