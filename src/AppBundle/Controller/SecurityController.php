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

            $translator = $this->get('translator');


            if(!$askPasswordManager->declareNewAskPassword($user)){
                $this->addFlash('error',
                    $translator->trans('app.common.errorComming') . " : <br />".implode('<br/>'.
                            $askPasswordManager->getErrors()));
                return $this->render('security/forgotPassword.html.twig', [
                    'form' => $formHandler->getForm()->createView()
                ]);
            }

            $this->addFlash('success',
                $translator->trans('app.security.forgotPassword.sendMailText')
            );
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

        $translator = $this->get('translator');

        if(null === $token){
            $this->addFlash('error', $translator->trans('app.security.changePassword.tokenNotFoundMessage'));
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
                    $translator->trans('app.security.changePassword.errorOnSaveNewPassword') . " <br />".
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