<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 25/03/2017
 * Time: 23:58
 */

namespace AppBundle\Form\Handler;


use AppBundle\Form\Type\ForgotPasswordFormType;
use AppBundle\Service\MailerTemplating;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Translation\Translator;

class ForgotPasswordFormHandler
{
    /**
     * @var EmailTemplating
     */
    private $mailTemplating;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    private $form;

    private $translator;

    /**
     * @var string
     */
    private $fromMail;

    public function __construct(FormFactory $formFactory, Translator $translator , MailerTemplating $mailTemplating, string $fromMail)
    {
        $this->form = $this->formFactory->create(ForgotPasswordFormType::class, null);

        $this->formFactory = $formFactory;
        $this->mailTemplating = $mailTemplating;
        $this->fromMail = $fromMail;
        $this->translator = $translator;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function process(Request $request)
    {
        $this->form->handleRequest($request);

        if($this->form->isSubmitted() && $this->form->isValid()){
            $this->sendMail($this->form->get('email'));
            return true;
        }

        return false;
    }

    public function sendMail($toMail)
    {
        $subject = $this->translator->trans('app.security.forgotPassword.emailSubject');

        $this->mailTemplating->send(
            ':email:forgotPassword.html.twig',
            array(),
            $subject,
            $this->fromMail,
            toMail
        );
    }
}