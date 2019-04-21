<?php
namespace AppBundle\DataFixtures\ORM;

use UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\UserGroup;

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

        $userGroup = new UserGroup();
        $userGroup->setActive(true)
            ->setLabel('Administrateurs')
        ;

        $manager->persist($userGroup);

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
                ->setActive(true)
                ->setCreateAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setGroup($userGroup)
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
                'username' => 'admin',
                'email' => 'your.email@you.com',
                'password' => 'admin123',
                'firstName' => 'No first name',
                'lastName' => 'No last name',
                'avatar' => 'bundles/user/images/avatars/default.png',
                'roles' => ['ROLE_ADMIN']
            ]
        ];
    }
}