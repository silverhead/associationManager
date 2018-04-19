<?php

namespace UserBundle\Manager;

use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserManager implements PaginatorManagerInterface
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

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator, SessionInterface $session )
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->session = $session;
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