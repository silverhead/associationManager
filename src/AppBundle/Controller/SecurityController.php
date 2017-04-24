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

            $askPasswordManager = $this->get('app.manager.security');
            $user = $askPasswordManager->getUserByEmail($email);

            if(!$askPasswordManager->declareNewAskPassword($user)){
                $this->addFlash('error',
                    "Une erreur est intervenue : <br />".implode('<br/>'.
                            $askPasswordManager->getErrors()));
                return $this->render('security/forgotPassword.html.twig', [
                    'form' => $formHandler->getForm()->createView()
                ]);
            }

            $this->addFlash('success',
                "Un e-mail vous a été envoyé, veuillez suivre les intructions qui y sont inscrites !");
            $askPasswordManager->sendAskNewPasswordMail($user);
        }

        return $this->render('security/forgotPassword.html.twig', [
            'form' => $formHandler->getForm()->createView()
        ]);
    }

    /**
     * @Route("/change-password", name="change_password")
     */
    public function changePassword(Request $request)
    {
        $token = $request->get('token');

        if(null === $token){
            $this->addFlash('error', "token non trouvé !");
            return $this->redirectToRoute('login');
        }

        $securyManager = $this->get('app.manager.security');
        $user = $securyManager->getUserByToken($token);

        $changePasswordHandler = $this->get('app.form.handler.security_change_password');
        $changePasswordHandler->setForm($user);

        if($changePasswordHandler->process($request)){
            $password = $changePasswordHandler->getNewPassword();

            if(!$securyManager->changeUserPassword($user, $password)){
                $this->addFlash(
                    'error',
                    "Une erreur est intervenur lors de l'enregistrement du nouveau mot de passe : <br />".
                    implode("<br />", $securyManager->getErrors())
                );

                return $this->render('security/changePassword.html.twig', [
                    'form' => $changePasswordHandler->getForm()->createView()
                ]);
            }

            return $this->render('security/changePasswordSuccess.html.twig');
        }

        return $this->render('security/changePassword.html.twig', [
            'form' => $changePasswordHandler->getForm()->createView()
        ]);
    }
}