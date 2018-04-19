<?php

namespace UserBundle\Form\Handler;

use AppBundle\Event\CredentialEvent;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\User\UserInterface;
use UserBundle\Entity\User;
use UserBundle\Entity\UserGroup;
use UserBundle\Form\Type\UserFormType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\Type\UserProfileFormType;

class UserFormHandler
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var CredentialEvent
     */
    protected $credentials;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

    /**
     * @var User
     */
    protected $entity;

    /**
     * UserGroupFormHandler constructor.
     * @param FormFactory $formFactory
     * @param $container
     */
    public function __construct(FormFactory $formFactory, Container $container){
        $this->formFactory = $formFactory;

        $this->container = $container;

        $credentialEvent = new CredentialEvent();
        $this->container ->get('event_dispatcher')->dispatch(
            CredentialEvent::EVENT_NAME,
            $credentialEvent
        );

        $this->credentials = $credentialEvent;
    }

    public function setForm(UserInterface $user = null, $profile = false)
    {
        $this->entity = $user;

        $formType = UserFormType::class;
        if($profile){
            $formType = UserProfileFormType::class;
        }

        $this->form = $this->formFactory->create($formType, $user, array(
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
        $form = $this->form;

        if(!empty($form['password']->getData())){
            $encoder = $this->container->get('security.password_encoder');

            $this->entity->setSalt(uniqid());

            $this->entity->setPassword(
                $encoder->encodePassword($this->entity, $form['password']->getData())
            );
        }

        return  $this->entity;
    }
}