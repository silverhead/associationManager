<?php

namespace EmailBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmailingController extends Controller
{
    /**
     * @Route("/emailing/list", name="email_emailing_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        return $this->render('EmailBundle:Emailing:list.html.twig', array(
            'menuSelect' => 'emailing_list',
        ));
    }
}
