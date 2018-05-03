<?php

namespace AppBundle\Manager;

use AppBundle\Entity\DashboardBundle;
use AppBundle\Entity\DashboardBundleSetting;
use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;

class DashboardBundleSettingManager implements PaginatorManagerInterface
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

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function getNewEntity()
    {
        return new DashboardBundleSetting();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("AppBundle:DashboardBundleSetting");
    }
}