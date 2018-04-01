<?php

namespace MemberBundle\Manager;

use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use MemberBundle\Entity\MemberStatus;
use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MemberStatusManager implements PaginatorManagerInterface
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

    private $session;

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator, SessionInterface $session )
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->session = $session;
    }

    public function getNewEntity()
    {
        return new MemberStatus();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("MemberBundle:MemberStatus");
    }
}