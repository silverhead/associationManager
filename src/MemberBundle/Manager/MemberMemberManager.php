<?php

namespace MemberBundle\Manager;

use AppBundle\Handler\ErrorHandlerTrait;
use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use AppBundle\Manager\PaginatorOrderedManagerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use MemberBundle\Entity\Member;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MemberMemberManager implements PaginatorManagerInterface
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

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        
        $this->session = $session;
    }

    public function getNewEntity()
    {
        return new Member();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("MemberBundle:Member");
    }
    
    public function getMemberNb(bool $onlyActive = false)
    {
        return $this->getRepository()->countMember($onlyActive);
    }

    public function getLastStatus(Member $member){
        return $this->entityManager->getRepository("MemberBundle:MemberStatusHistorical")->getLastStatusOfMember($member);
    }
}