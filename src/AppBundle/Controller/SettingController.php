<?php

namespace AppBundle\Controller;

use AppBundle\Form\Model\SettingAppModel;
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
        $formSettingAppHandler = $this->get('app.form.handler.setting_app');

        $settingManager = $this->get('app.manager.setting');

        $settingApp = new SettingAppModel();
        $settingApp
            ->setLogo(
                $settingManager->getSetting('app.setting.logo')->getValue()
            )
            ->setAssociationName(
                $settingManager->getSetting('app.setting.association_name')->getValue()
            )
            ->setContactEmail(
                $settingManager->getSetting('app.setting.contact_email')->getValue()
            )
            ->setRobotEmail(
                $settingManager->getSetting('app.setting.robot_email')->getValue()
            )
            ->setPhone(
                $settingManager->getSetting('app.setting.phone')->getValue()
            )
            ->setDescription(
                $settingManager->getSetting('app.setting.description')->getValue()
            )
            ->setCountry(
                $settingManager->getSetting('app.setting.country')->getValue()
            )
            ->setCity(
                $settingManager->getSetting('app.setting.city')->getValue()
            )
            ->setZipcode(
                $settingManager->getSetting('app.setting.zipcode')->getValue()
            )
            ->setAddress(
                $settingManager->getSetting('app.setting.address')->getValue()
            )
        ;

        $formSettingAppHandler->setForm($settingApp);

        return $this->render('setting/settingManager.html.twig', array(
            'menuSelect' => 'setting_manager',
            'formSetting' => $formSettingAppHandler->getForm()->createView()
        ));
    }
}
