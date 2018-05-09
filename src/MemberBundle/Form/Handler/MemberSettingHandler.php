<?php

namespace MemberBundle\Form\Handler;

use MemberBundle\Form\Model\MemberSettingModel;
use MemberBundle\Form\Type\MemberSettingFormType;
use AppBundle\Manager\SettingManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class MemberSettingHandler
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
     * @var MemberSettingModel
     */
    private $memberSettingModel;

    /**
     * @var SettingManager
     */
    private $settingManager;

    public function __construct(FormFactory $formFactory, MemberSettingModel  $memberSettingModel, SettingManager $settingManager){
        $this->formFactory = $formFactory;
        $this->memberSettingModel = $memberSettingModel;
        $this->settingManager = $settingManager;
    }

    public function setForm()
    {
        $this->initSettingModel();
        $this->form = $this->formFactory->create(MemberSettingFormType::class, $this->memberSettingModel);
    }

    public function getForm()
    {
        if(null === $this->form){
            throw new Exception("the form is not init, please use ::setForm before!");
        }

        return $this->form;
    }

    public function initSettingModel()
    {
        $this->memberSettingModel
            ->setGender(
                $this->settingManager->getSetting('member.setting.gender')->getValue()
            )
            ->setStudy(
                $this->settingManager->getSetting('member.setting.study')->getValue()
            )
            ->setExpertise(
                $this->settingManager->getSetting('member.setting.expertise')->getValue()
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

        $this->settingManager->save('member.setting.gender', $data->getGender());
        $this->settingManager->save('member.setting.study', $data->getStudy());
        $this->settingManager->save('member.setting.expertise', $data->getExpertise());

        $this->setForm();
    }
}