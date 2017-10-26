<?php

namespace UserBundle\Manager;

use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorOrderedManagerInterface;
use AppBundle\Manager\PaginatorOrderedManagerTrait;
use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserManager implements PaginatorOrderedManagerInterface
{
    use EntityManagerTrait,
        PaginatorOrderedManagerTrait,
        ErrorHandlerTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator, SessionInterface $session, string $paginatorNamespace, array $orderedTableCorrespondance )
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->session = $session;
        
        $this->setPaginatorOrederedNamespace($paginatorNamespace);
        $this->setOrderedTableCorrespondence($orderedTableCorrespondance);
    }

    public function getNewEntity()
    {
        return new User();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("UserBundle:User");
    }

}