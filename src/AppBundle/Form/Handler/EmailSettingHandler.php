<?php

namespace AppBundle\Form\Handler;

use AppBundle\Form\Model\EmailSettingModel;
use AppBundle\Form\Type\EmailSettingFormType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class EmailSettingHandler
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

    public function setForm(EmailSettingModel  $model)
    {
        $this->form = $this->formFactory->create(EmailSettingFormType::class, $model);
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

    public function getData()
    {
        return  $this->form->getData();
    }
}