<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 12/04/2017
 * Time: 22:56
 */

namespace AppBundle\Form\Handler;


use AppBundle\Form\Type\ChangePasswordFormType;
use Symfony\Component\Form\FormFactory;

class ChangePasswordHandler
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

        $this->form = $this->formFactory->create(ChangePasswordFormType::class);
    }

    public function getForm()
    {
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
        return $this->form->getData();
    }
}