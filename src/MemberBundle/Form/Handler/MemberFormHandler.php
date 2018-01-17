<?php

namespace MemberBundle\Form\Handler;

use MemberBundle\Entity\Member;
use MemberBundle\Form\Type\MemberFormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use MemberBundle\Entity\MemberStatusHistorical;

class MemberFormHandler
{
    
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

    public function __construct(FormFactory $formFactory){
        $this->formFactory = $formFactory;
    }

    public function setForm(Member $member = null)
    {
        $this->member = $member;
        
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
        $form = $this->form;
        
        //Identity
        $this->member->setFirstName($form['firstName']->getData());
        $this->member->setLastName($form['lastName']->getData());
        $this->member->setGender($form['gender']->getData());
        $this->member->setBirthday($form['birthday']->getData());
        //Coordonate
        $this->member->setCountry($form['country']->getData());
        $this->member->setCity($form['city']->getData());
        $this->member->setZipcode($form['zipcode']->getData());
        $this->member->setAddress($form['address']->getData());
        $this->member->setPhone($form['phone']->getData());
        $this->member->setCellular($form['cellular']->getData());
        // Connection
        $this->member->setUsername($form['username']->getData());
        $this->member->setEmail($form['email']->getData());
        
        $this->member->setGroup($form['group']->getData());
        
        $this->member->setRoles(array('ROLE_USER'));
        
        $status = $form['status']->getData();
        
        $this->member->setStatus(new ArrayCollection());
        
        $statusHistorical = new MemberStatusHistorical();
        $this->member->addStatus($statusHistorical);
        $status->addMember($statusHistorical);
        
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