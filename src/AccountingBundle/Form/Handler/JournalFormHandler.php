<?php

namespace AccountingBundle\Form\Handler;

use AccountingBundle\Entity\Journal;
use AccountingBundle\Form\Type\JournalFormType;
use Symfony\Component\DependencyInjection\Container;
use AccountingBundle\Entity\AccountableAccount;
use AccountingBundle\Form\Type\AccountableAccountFormType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class JournalFormHandler
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
     * @var Journal
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

    public function setForm(Journal $journal) {
        $this->entity = $journal;

        $this->form = $this->formFactory->create(JournalFormType::class, $journal);
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
