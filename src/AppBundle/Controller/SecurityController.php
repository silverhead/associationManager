<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 22/03/2017
 * Time: 22:25
 */

namespace AppBundle\Controller;

use AppBundle\Form\Type\ChangePasswordFormType;
use AppBundle\Form\Type\ForgotPasswordFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $error = $this->get('security.authentication_utils')
            ->getLastAuthenticationError();

        return $this->render(
            'security/login.html.twig', [
                'error' => $error
            ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }

    /**
     * @Route("/forgot-password", name="forgot_password")
     * @param Request $request
     */
    public function forgotPassword(Request $request)
    {
        $formHandler = $this->get('app.form.handler.security_forgot_password');

        if($formHandler->process($request))
        {
            $data = $formHandler->getData();
            $email = $data['email'];

            $askPasswordManager = $this->get('app.manager.security_ask_password');
            $user = $askPasswordManager->getUserByEmail($email);

            $askPasswordManager->setNewAskPasswordToken($user);

            $askPasswordManager->sendAskNewPasswordMail($user);
        }

//        $form = $this->createForm(ForgotPasswordFormType::class, null);
//
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid()){
//            return $this->redirectToRoute('login');
//        }

        return $this->render('security/forgotPassword.html.twig', [
            'form' => $formHandler->getForm()->createView()
        ]);
    }

    /**
     * @Route("/change-password", name="change_password")
     */
    public function changePassword()
    {
        $changePasswordHandler = $this->get('app.form.handler.security_change_password');

        if($changePasswordHandler->process()){

        }


        return $this->render('security/changePassword.html.twig', [
            'form' => $changePasswordHandler->getForm()->createView()
        ]);
    }
}