<?php

namespace UserBundle\Form\Handler;

use AppBundle\Event\CredentialEvent;
use Symfony\Component\DependencyInjection\Container;
use UserBundle\Entity\User;
use UserBundle\Entity\UserGroup;
use UserBundle\Form\Type\UserFormType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class UserFormHandler
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var CredentialEvent
     */
    private $credentials;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    private $form;

    /**
     * UserGroupFormHandler constructor.
     * @param FormFactory $formFactory
     * @param $container
     */
    public function __construct(FormFactory $formFactory, Container $container){
        $this->formFactory = $formFactory;

        $credentialEvent = new CredentialEvent();
        $container->get('event_dispatcher')->dispatch(
            CredentialEvent::EVENT_NAME,
            $credentialEvent
        );

        $this->credentials = $credentialEvent;
    }

    public function setForm(User $user)
    {
        $this->form = $this->formFactory->create(UserFormType::class, $user, array(
            'credentialsList' => $this->credentials
        ));
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