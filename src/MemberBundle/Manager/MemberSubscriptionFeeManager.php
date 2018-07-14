<?php

namespace MemberBundle\Manager;

use AppBundle\Handler\ErrorHandlerTrait;
use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use MemberBundle\Entity\MemberSubscriptionFee;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MemberSubscriptionFeeManager implements PaginatorManagerInterface
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
        return new MemberSubscriptionFee();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("MemberBundle:MemberSubscriptionFee");
    }

    public function getTotalPaidFee(){
        return $this->getRepository()->getTotalFee(true);
    }

    public function getLatePaymentFeeByMemberIdList(array $membersIdList)
    {
        return $this->getRepository()->getLatePaymentFeeByMemberIdList($membersIdList);
    }

    public function getLatePaymentFeeMemberList($limit = 10, $order = array())
    {
        return $this->getRepository()->getLatePaymentFeeMemberList($limit, $order);
    }

    public function getSoonFeeNewPaymentListByMemberIdList(\DateTime $startPeriod, \DateTime $endPeriod, array $memberIdList)
    {
        return $this->getRepository()->getSoonFeeNewPaymentListByMemberIdList($startPeriod, $endPeriod, $memberIdList);
    }

    public function getSoonFeeNewPaymentMemberList(\DateTime $startPeriod, \DateTime $endPeriod, $limit = 10, $order = array())
    {
        return $this->getRepository()->getSoonFeeNewPaymentMemberList($startPeriod, $endPeriod, $limit, $order);
    }

    public function getSoonFeeNewPaymentMemberIdList(\DateTime $startPeriod, \DateTime $endPeriod, $limit = 10, $order = array())
    {
        return $this->getRepository()->getSoonFeeNewPaymentMemberIdList($startPeriod, $endPeriod, $limit, $order);
    }

    public function getLatePaymentFeeMemberIdList()
    {
        return $this->getRepository()->getLatePaymentFeeMemberIdList();
    }
}