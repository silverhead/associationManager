<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 22/03/2017
 * Time: 22:25
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}