<?php
namespace UserBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package UserBundle\Repository
 */
class UserRepository extends EntityRepository implements PaginatorRepositoryInterface
{
    public function getQbPaginatedList()
    {
        return $this->createQueryBuilder("u");
    }

    public function findByUsernameOrEmail($username)
    {
        return $this->createQueryBuilder("u")
            ->where("u.username = :username")
            ->orWhere("u.email = :username")
            ->setParameter("username", $username)
            ->getQuery()->getOneOrNullResult()
            ;
    }
}