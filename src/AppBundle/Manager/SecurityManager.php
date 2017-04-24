<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 27/03/2017
 * Time: 22:36
 */

namespace AppBundle\Manager;


use AppBundle\Entity\User;
use AppBundle\Service\MailerTemplating;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Translation\Translator;

class SecurityManager
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
    /**
     * @var MailerTemplating
     */
    private $emailTemplating;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var string
     */
    private $templatingPath;

    /**
     * @var string
     */
    private $robotMail;

    /**
     * @var UserPasswordEncoder
     */
    private $userPasswordEncoder;

    public function __construct(EntityManager $em, UserPasswordEncoder $userPasswordEncoder, Translator $translator, MailerTemplating $emailTemplating, $templatingPath, $robotMail)
    {
        $this->em = $em;
        $this->emailTemplating = $emailTemplating;

        $this->userRepo = $this->em->getRepository("AppBundle:User");

        $this->translator = $translator;
        $this->templatingPath = $templatingPath;
        $this->robotMail = $robotMail;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function getUserByEmail(string $email): User
    {
        return $this->userRepo->findOneByEmail($email);
    }

    public function getUserByToken(string $token): User
    {
        return $this->userRepo->findOneByAskPasswordToken($token);
    }

    public function setNewAskPasswordToken(User $user)
    {
        $user->setAskPasswordToken(uniqid());
        $this->em->flush();
    }

    public function declareNewAskPassword(User $user): bool
    {
        try{
            $user->setLastDateAskNewPassword(new \DateTime())
                ->setAskPasswordToken(uniqid())
            ;

            $this->em->flush();

            return true;
        }
        catch(\Exception $e){
            $this->errors[] = $e->getCode()." : ".$e->getMessage();

            return false;
        }
    }

    public function sendAskNewPasswordMail(User $user)
    {
        $this->emailTemplating->send(
            $this->templatingPath,
            ['name' => $user->getFullName(), 'token' => $user->getAskPasswordToken()],
            $this->translator->trans('app.security.askNewPasswordMail.subject'),
            $this->robotMail,
            $user->getEmail()
        );
    }


    public function changeUserPassword(User $user, string $password)
    {
        try{
            $user->setPassword(
                $this->userPasswordEncoder->encodePassword($user, $password)
            );
            $this->em->flush();

            return true;
        }
        catch(\Exception $e){

            $this->errors[] = $e->getMessage();

            return false;
        }

    }

    public function getErrors()
    {
        return $this->errors;
    }
}