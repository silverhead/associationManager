<?php

namespace SubscriptionBundle\Form\Handler;

use SubscriptionBundle\Entity\Subscription;
use SubscriptionBundle\Form\Type\SubscriptionFormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionFormHandler
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

    public function setForm(Subscription $subscription = null)
    {

        $this->form = $this->formFactory->create(SubscriptionFormType::class, $subscription);
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