<?php

namespace EmailBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SystemEmailController
 * @package EmailBundle\Controller
 */
class SystemEmailController extends Controller
{
    /**
     * @Route("/setting/email/list", name="app_email_setting")
     */
    public function listAction()
    {
        return $this->render("EmailBundle:systemEmail:list.html.twig");
    }
}