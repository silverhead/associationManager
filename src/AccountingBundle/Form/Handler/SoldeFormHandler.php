<?php

namespace AccountingBundle\Form\Handler;

use AccountingBundle\Entity\Solde;
use AccountingBundle\Form\Type\SoldeFormType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use AccountingBundle\Manager\AccountingManager;

class SoldeFormHandler
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var AccoutingManager
     */
    protected $manager;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

    /**
     * @var Solde
     */
    protected $entity;

    /**
     * AccountFormHandler constructor
     * @param FormFactory $formFactory
     * @param $manager
     */
    public function __construct(FormFactory $formFactory, AccountingManager $manager) {
        $this->formFactory = $formFactory;
        $this->manager = $manager;
    }

    public function setForm(Solde $solde, $id) {
        $this->entity = $solde;

        //$accountableAccount = $this->manager->getSoldesForAccount($id);
        $this->form = $this->formFactory->create(SoldeFormType::class, $solde, array(
            //'accountableAccount' => $accountableAccount
        ));
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

    public function getData($accountableAccount) {
        $form = $this->form;
        
        $this->entity->setAccountableAccount($accountableAccount);
        $this->entity->setIsPrev(false);
        $this->entity->setUpdatedAt(new \DateTime());
        
        return $this->entity;
    }
}
