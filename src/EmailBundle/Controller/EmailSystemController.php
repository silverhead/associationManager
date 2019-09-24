<?php

namespace EmailBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class EmailSystemController
 * @package EmailBundle\Controller
 */
class EmailSystemController extends Controller
{
    /**
     * @Route("/setting/email/list", name="app_email_setting")
     */
    public function listAction()
    {
        $emailSystemRepo = $this->getDoctrine()->getRepository('EmailBundle:EmailSystem');

        $emailSystemList = $emailSystemRepo->findAll([],[
            'label' => 'ASC'
        ]);

        return $this->render("EmailBundle:EmailSystem:list.html.twig", [
            'emailList' => $emailSystemList
        ]);
    }
}