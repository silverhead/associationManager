<?php

namespace UserBundle\Form\Handler;

use UserBundle\Form\Model\UserListFilterModel;
use UserBundle\Form\Type\UserListFilterFormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class UserListFilterHandler
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    private $form;

    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function setForm(UserListFilterModel $modelListFilterModel = null)
    {
        $this->form = $this->formFactory->create(UserListFilterFormType::class, $modelListFilterModel);
    }

    public function getForm()
    {
        if(null === $this->form){
            throw new \Exception("the form is not init, please use ::setForm before!");
        }

        return $this->form;
    }

    public function process(Request $request)
    {
        if('POST' !== $request->getMethod()){
            return false;
        }

        $this->form->handleRequest($request);

        if($this->form->isSubmitted()){ //&& $this->form->isValid()
            return true;
        }

        return false;
    }

    public function getData()
    {
        return  $this->form->getData();
    }
}