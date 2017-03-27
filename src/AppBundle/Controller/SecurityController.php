<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 22/03/2017
 * Time: 22:25
 */

namespace AppBundle\Controller;

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
        $formHandler = $this->get('app_form_handler_security_forgot_password');

        if($formHandler->process($request))
        {
            $data = $formHandler->getData();
            $email = $data['email'];

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
        return $this->render('');
    }
}