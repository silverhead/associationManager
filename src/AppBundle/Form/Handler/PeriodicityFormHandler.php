<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\SubscriptionPaymentPeriodicity;
use AppBundle\Form\Type\PeriodicityFormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class PeriodicityFormHandler
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

    public function setForm(SubscriptionPaymentPeriodicity $periodicity = null)
    {

        $this->form = $this->formFactory->create(PeriodicityFormType::class, $periodicity);
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