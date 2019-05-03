<?php

namespace EmailBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class EmailAutomaticController
 * @package EmailBundle\Controller
 * @Route("email/automatic_email/", name="email_automatic_email")
 */
class AutomaticEmailController extends Controller
{
    /**
     * @Route("list", name="email_automatic_email_list")
     */
    public function listAction()
    {
        return $this->render("EmailBundle:automaticEmail:list.html.twig");
    }
}