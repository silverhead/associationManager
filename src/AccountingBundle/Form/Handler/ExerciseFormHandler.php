<?php

namespace AccountingBundle\Form\Handler;

use Symfony\Component\DependencyInjection\Container;
use AccountingBundle\Entity\Exercise;
use AccountingBundle\Form\Type\ExerciseFormType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use AccountingBundle\Manager\ExerciseManager;

class ExerciseFormHandler
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Container
     */
    protected $manager;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

    /**
     * @var Entry
     */
    protected $entity;

    /**
     * AccountFormHandler constructor.
     * @param FormFactory $formFactory
     * @param $container
     */
    public function __construct(FormFactory $formFactory, ExerciseManager $manager) {
        $this->formFactory = $formFactory;

        $this->manager = $manager;
    }

    public function setForm(Exercise $exercise) {
        $this->entity = $exercise;

        $this->form = $this->formFactory->create(ExerciseFormType::class, $exercise);
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
        $form = $this->form;
        
        $this->entity->setActive(true);
        $this->entity->setUpdatedAt(new \DateTime());
        
        return $this->entity;
    }
}
