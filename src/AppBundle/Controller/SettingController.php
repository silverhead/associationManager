<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingController extends Controller
{
    /**
     * @Route("/setting_maanger", name="setting_manager")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ManagerAction()
    {
        $settingApp = (object) [
            'logo' => '',
            'name' => '',
            'description' => '',
            'country' => '',
            'city' => '',
            'zipCode' => '',
            'address' => '',
            'emailContact' => '',
            'emailRobot' => '',
            'phone' => '',
        ];

        return $this->render('setting/settingManager.html.twig', array(
            'settingApp' => $settingApp,
            'menuSelect' => 'setting_manager'
        ));
    }
}
