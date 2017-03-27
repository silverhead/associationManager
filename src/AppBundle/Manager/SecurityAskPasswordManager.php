<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 27/03/2017
 * Time: 22:36
 */

namespace AppBundle\Manager;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class SecurityAskPasswordManager
{

    /**
     * @var EntityManager
     */
    private $em;

    private $userRepo;

    /**
     * @var User
     */
    private $user;

    /**
     * @var array
     */
    private $errors = [];

    public function __construct(EntityManager $em)
    {
        $this->em = $em;

        $this->userRepo = $this->em->getRepository("AppBundle:User");
    }

    public function getUserByEmail(string $email): User
    {
        return $this->userRepo->findOneByEmail($email);
    }

    public function declareNewAskPassword(User $user): bool
    {
        try{
            $user->setLastDateAskNewPassword(new \DateTime())
                ->setTokenAskNewPassword(uniqid())
            ;

            $this->em->flush();

            return true;
        }
        catch(\Exception $e){
            $errors[] = $e->getCode()." : ".$e->getMessage();

            return false;
        }
    }
}