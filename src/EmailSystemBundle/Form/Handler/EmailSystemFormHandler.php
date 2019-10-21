<?php

namespace EmailSystemBundle\Form\Handler;

use EmailSystemBundle\Entity\EmailSystem;
use EmailBundle\Form\Type\EmailFormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class EmailSystemFormHandler
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

    /**
     * @var EmailSystem
     */
    protected $entity;

    /**
     * UserGroupFormHandler constructor.
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory){
        $this->formFactory = $formFactory;
    }

    public function getForm(EmailSystem $email = null)
    {
        $formType = EmailFormType::class;
        $this->form = $this->formFactory->create($formType, $email, array());

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
        return  $this->entity;
    }
}