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
use AppBundle\Manager\PaginatorOrderedManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MemberMemberManager implements PaginatorOrderedManagerInterface
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
        return new Member();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("MemberBundle:Member");
    }
    
    public function getMemberNb()
    {
        return $this->getRepository()->countMember();
    }

    public function getLastStatus(Member $member){
        return $this->entityManager->getRepository("MemberBundle:MemberStatusHistorical")->getLastStatusOfMember($member);
    }
}