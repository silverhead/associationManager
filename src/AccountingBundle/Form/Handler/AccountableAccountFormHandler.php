<?php

namespace AccountingBundle\Form\Handler;

use Symfony\Component\DependencyInjection\Container;
use AccountingBundle\Entity\AccountableAccount;
use AccountingBundle\Form\Type\AccountableAccountFormType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class AccountableAccountFormHandler
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

    /**
     * @var AccountableAccount
     */
    protected $entity;

    /**
     * AccountFormHandler constructor.
     * @param FormFactory $formFactory
     * @param $container
     */
    public function __construct(FormFactory $formFactory, Container $container) {
        $this->formFactory = $formFactory;

        $this->container = $container;
    }

    public function setForm(AccountableAccount $account) {
        $this->entity = $account;

        $this->form = $this->formFactory->create(AccountableAccountFormType::class, $account);
    }

    public function getForm() {
        if ($this->form === null) {
            throw new Exception("The form is not init, please use ::setForm before!");
        }

        return $this->form;
    }

    public function process(Request $request) {
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            return true;
        }

        return false;
    }

    public function getData() {
        return $this->entity;
    }
}
