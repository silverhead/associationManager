<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadSettingData implements FixtureInterface, ContainerAwareInterface
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

    }

    public function getFakeSetting()
    {
        $settings = [
            (object) [
                'code' => 'app.setting.association_name',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.contact_email',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.robot_email',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.phone',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.country',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.city',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.zipcode',
                'type' => 'string',
                'value' => '',
            ],
            (object) [
                'code' => 'app.setting.address',
                'type' => 'string',
                'value' => '',
            ]
        ];
    }
}