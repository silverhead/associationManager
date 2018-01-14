<?php

namespace MemberBundle\Form\Handler;

use MemberBundle\Entity\Member;
use MemberBundle\Form\Type\MemberFormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class MemberFormHandler
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

    public function setForm(Member $member = null)
    {
        $currentAvatar = '/images/avatars/user.png';
        
        $this->form = $this->formFactory->create(MemberFormType::class, $member, [
            'currentAvatar' => $currentAvatar
        ]);
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