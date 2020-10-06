<?php

namespace AppBundle\Controller;

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

        if(null !== $error){
            $translator = $this->get('translator');
            if(false === $error->getToken()->isAuthenticated()){
                $this->addFlash('error', $translator->trans('app.security.login.error.authenticateFail'));
            }
        }


        return $this->render(
            'security/login.html.twig');
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
        $settingManager = $this->get('app.manager.setting');

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
            'form' => $formHandler->getForm()->createView(),
            'logo' => $settingManager->getFormatedSettingValue('app.setting.association_name'),
            'associationName' => $settingManager->getFormatedSettingValue('app.setting.association_name'),
            'slogan' => $settingManager->getFormatedSettingValue('app.setting.description'),
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