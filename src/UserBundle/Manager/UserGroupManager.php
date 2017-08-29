<?php

namespace UserBundle\Manager;

use AppBundle\Manager\EntityManagerTrait;
use AppBundle\Manager\PaginatorManagerInterface;
use AppBundle\Manager\PaginatorManagerTrait;
use UserBundle\Entity\UserGroup;
use AppBundle\Handler\ErrorHandlerTrait;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class UserGroupManager implements PaginatorManagerInterface
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

    public function __construct(EntityManager $entityManager, PaginatorInterface $paginator )
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function getNewEntity()
    {
        return new UserGroup();
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository("UserBundle:UserGroup");
    }

    public function saveAjax(Request $request){
        $id = $request->get('id', null);
        $label = $request->get('label', null);

        if(null !== $id){
            $status = $this->find($id);
        }
        else{
            $status = $this->getNewEntity();
        }

        $status->setLabel($label);

        $this->entityManager->persist($status);
        $this->entityManager->flush();
    }
}