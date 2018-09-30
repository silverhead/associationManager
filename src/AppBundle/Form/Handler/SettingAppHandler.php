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
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    private $rootDir;

    public function __construct(FormFactory $formFactory, SettingAppModel  $settingAppModel, SettingManager $settingManager, $rootDir){
        $this->formFactory = $formFactory;
        $this->settingAppModel = $settingAppModel;
        $this->settingManager = $settingManager;

        $this->rootDir = $rootDir;
    }

    public function setForm()
    {
        $currentLogo = $this->settingManager->getSettingValue('app.setting.logo');

        if('' === $currentLogo){
            $currentLogo= null;
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
            ->setPhone(
                $this->settingManager->getSetting('app.setting.phone')->getValue()
            )
            ->setGsm(
                $this->settingManager->getSetting('app.setting.gsm')->getValue()
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
            ->setUrlFacebook(
                $this->settingManager->getSetting('app.setting.url_facebook')->getValue()
            )
            ->setUrlTwitter(
                $this->settingManager->getSetting('app.setting.url_twitter')->getValue()
            )
            ->setApplicationName(
                $this->settingManager->getSetting('app.setting.name')->getValue()
            )
            ->setApplicationDescription(
                $this->settingManager->getSetting('app.setting.description')->getValue()
            )
        ;
    }

    public function process(Request $request)
    {
        $this->form->handleRequest($request);

        if($this->form->isSubmitted() && $this->form->isValid()){
            $this->saveSetting();

            return true;
        }

        return false;
    }

    private function saveSetting()
    {
        $data = $this->form->getData();

        $this->settingManager->save('app.setting.association_name', $data->getAssociationName());
        $this->settingManager->save('app.setting.contact_email', $data->getContactEmail());
        $this->settingManager->save('app.setting.phone', $data->getPhone());
        $this->settingManager->save('app.setting.gsm', $data->getPhone());
        $this->settingManager->save('app.setting.description', $data->getDescription());
        $this->settingManager->save('app.setting.country', $data->getCountry());
        $this->settingManager->save('app.setting.city', $data->getCity());
        $this->settingManager->save('app.setting.zipcode', $data->getZipcode());
        $this->settingManager->save('app.setting.address', $data->getAddress());

        $this->settingManager->save('app.setting.url_facebook', $data->getUrlFacebook());
        $this->settingManager->save('app.setting.url_twitter', $data->getUrlTwitter());

        $this->settingManager->save('app.setting.name', $data->getApplicationName());
        $this->settingManager->save('app.setting.description', $data->getApplicationDescription());

        if(null !== $data->getLogo()){
            $logoFullPath = $this->saveLogo($data->getLogo());
            $this->settingManager->save('app.setting.logo', $logoFullPath);
        }

        $this->setForm();
    }

    private function saveLogo($logoFile)
    {
        $webDir = $this->rootDir.'/../web';

        $logoExt  = $logoFile->guessExtension();
        $logoName = 'logo.'.$logoExt;
        $path =  '/images/logo/';

        $logoFile->move(
            $webDir.$path,
            $logoName
        );
        return $path.$logoName;
    }
}