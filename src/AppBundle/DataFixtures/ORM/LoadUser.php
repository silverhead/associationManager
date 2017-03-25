<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        $users = $this->getUsers();

        foreach ($users as $fakeUser){
            $user = new User();

            $user
                ->setUsername(
                    $fakeUser->username
                )
                ->setEmail(
                    $fakeUser->email
                )
                ->setPassword(
                    $fakeUser->password
                )
                ->setFirstName(
                    $fakeUser->firstName
                )
                ->setLastName(
                    $fakeUser->lastName
                )
                ->setRoles(
                    $fakeUser->roles
                )
                ->setSalt(
                    uniqid()
                )
            ;

            $user->setPassword(
                $encoder->encodePassword($user, $fakeUser->password)
            );


            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getUsers()
    {
        return [
            (object) [
                'username' => 'pin.nicolas',
                'email' => 'pin.nicolas@free.fr',
                'password' => 'test123',
                'firstName' => 'Nicolas',
                'lastName' => 'PIN',
                'roles' => ['ROLE_ADMIN']
            ],
            (object) [
                'username' => 'test1',
                'email' => 'test@test.fr',
                'password' => 'test123',
                'firstName' => 'test1',
                'lastName' => 'test',
                'roles' => ['ROLE_USER']
            ],
        ];
    }
}