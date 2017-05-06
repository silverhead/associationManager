<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 06/05/2017
 * Time: 20:10
 */

namespace AppBundle\Manager;


use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;

class SubscriptionManager implements EntityManagerInterface, ErrorHandlerInterface
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
        // TODO: Implement find() method.
    }

    public function save($entity)
    {
        // TODO: Implement save() method.
    }

    public function delete($entity)
    {
        // TODO: Implement delete() method.
    }

    public function getRepository()
    {
        $this->entityManager->getRepository("");
    }


}