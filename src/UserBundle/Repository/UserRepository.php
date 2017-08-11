<?php
namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package UserBundle\Repository
 */
class UserRepository extends EntityRepository
{
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