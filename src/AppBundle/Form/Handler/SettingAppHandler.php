<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 27/04/2017
 * Time: 22:12
 */

namespace AppBundle\Form\Handler;

use AppBundle\Form\Model\SettingAppModel;
use AppBundle\Form\Type\SettingAppFormType;
use AppBundle\Manager\SettingManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class SettingAppHandler
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    private $form;

    /**
     * @var SettingAppModel
     */
    private $settingAppModel;

    /**
     * @var SettingManager
     */
    private $settingManager;

    public function __construct(FormFactory $formFactory, SettingAppModel  $settingAppModel, SettingManager $settingManager){
        $this->formFactory = $formFactory;
        $this->settingAppModel = $settingAppModel;
        $this->settingManager = $settingManager;
    }

    public function setForm()
    {
        $currentLogo = $this->settingManager->getSettingValue('app.setting.logo');

        if('' === $currentLogo){
            $currentLogo= '/images/avatars/user.png';
        }

        $this->initAppSettingModel();
        $this->form = $this->formFactory->create(SettingAppFormType::class, $this->settingAppModel, [
            'currentLogo' => $currentLogo
        ]);
    }

    public function getForm()
    {
        if(null === $this->form){
            throw new Exception("the form is not init, please use ::setForm before!");
        }

        return $this->form;
    }

    public function initAppSettingModel()
    {
        $this->settingAppModel
            ->setAssociationName(
                $this->settingManager->getSetting('app.setting.association_name')->getValue()
            )
            ->setContactEmail(
                $this->settingManager->getSetting('app.setting.contact_email')->getValue()
            )
            ->setRobotEmail(
                $this->settingManager->getSetting('app.setting.robot_email')->getValue()
            )
            ->setPhone(
                $this->settingManager->getSetting('app.setting.phone')->getValue()
            )
            ->setDescription(
                $this->settingManager->getSetting('app.setting.description')->getValue()
            )
            ->setCountry(
                $this->settingManager->getSetting('app.setting.country')->getValue()
            )
            ->setCity(
                $this->settingManager->getSetting('app.setting.city')->getValue()
            )
            ->setZipcode(
                $this->settingManager->getSetting('app.setting.zipcode')->getValue()
            )
            ->setAddress(
                $this->settingManager->getSetting('app.setting.address')->getValue()
            )
        ;
    }

    public function process(Request $request)
    {
        $this->form->handleRequest($request);

//        $data = $this->form->getData();

        if($this->form->isSubmitted() && $this->form->isValid()){
            $this->saveSetting();

            return true;
        }

        return false;
    }

    public function getData()
    {
        return $this->form->getData();
    }

    private function saveSetting()
    {
        $data = $this->form->getData();

        dump($data->getLogo());

        $data->getLogo()->move(
            '/images/avatars/',
            'test.png'
        );
    }
}