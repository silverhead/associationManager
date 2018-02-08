<?php

namespace MemberBundle\Manager;

use AppBundle\Handler\ErrorHandlerTrait;
use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use MemberBundle\Entity\Member;
use MemberBundle\Entity\MemberSubscriptionFee;
use MemberBundle\Entity\MemberSubscriptionHistorical;

class MemberSubscriptionHistoricalManager implements PaginatorManagerInterface
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
        return new MemberSubscriptionHistorical();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("MemberBundle:MemberSubscriptionHistorical");
    }

    public function getListByMember(Member $member, int $limit = 0, array $orders = array('msh.endDate' => 'desc'))
    {
        $result = $this->getRepository()->getListByMember($member, $limit, $orders);

        return $result;
    }
}