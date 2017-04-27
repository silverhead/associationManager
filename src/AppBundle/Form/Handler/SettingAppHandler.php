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

    public function __construct(FormFactory $formFactory){
        $this->formFactory = $formFactory;
    }

    public function setForm(SettingAppModel $settingAppModel)
    {
        $this->form = $this->formFactory->create(SettingAppFormType::class, $settingAppModel);
    }

    public function getForm()
    {
        if(null === $this->form){
            throw new Exception("the form is not init, please use ::setForm before!");
        }

        return $this->form;
    }

    public function process(Request $request)
    {
        $this->form->handleRequest($request);

        if($this->form->isSubmitted() && $this->form->isValid()){
            return true;
        }

        return false;
    }

    public function getNewPassword()
    {
        $data = $this->form->getData();

        return $data['password'];
    }
}