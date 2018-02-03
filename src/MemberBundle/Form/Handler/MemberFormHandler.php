<?php

namespace MemberBundle\Form\Handler;

use AppBundle\Manager\EntityManagerInterface;
use MemberBundle\Entity\Member;
use MemberBundle\Form\Type\MemberFormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use MemberBundle\Entity\MemberStatusHistorical;

class MemberFormHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var Member
     */
    private $member; 
    
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    private $form;

    public function __construct(FormFactory $formFactory, EntityManagerInterface $entityManager){
        $this->formFactory = $formFactory;

        $this->entityManager = $entityManager;
    }

    public function setForm(Member $member = null)
    {
        $this->member = $member;
        
        $currentAvatar = '/images/avatars/user.png';
        
        $this->form = $this->formFactory->create(MemberFormType::class, $member, [
            'currentAvatar' => $currentAvatar,
            'entity' => $this->member
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
        $form = $this->form;

        $this->member->setAvatar('user.png');//@todo set file upload

        if(!empty($form['password']->getData())){
            $encoder = $this->get('security.password_encoder');
            
            $this->member->setSalt(uniqid());
            
            $this->member->setPassword(
                $encoder->encodePassword($this->member, $form['password']->getData())
                );
        }
        
        return  $this->member;
    }
}