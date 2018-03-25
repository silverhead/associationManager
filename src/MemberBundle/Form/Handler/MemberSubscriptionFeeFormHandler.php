<?php

namespace MemberBundle\Form\Handler;

use MemberBundle\Entity\MemberSubscriptionFee;
use MemberBundle\Form\Type\MemberSubscriptionFeeFormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class MemberSubscriptionFeeFormHandler
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

    public function setForm(MemberSubscriptionFee $memberSubscriptionFee = null)
    {
        $this->form = $this->formFactory->create(MemberSubscriptionFeeFormType::class, $memberSubscriptionFee);
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