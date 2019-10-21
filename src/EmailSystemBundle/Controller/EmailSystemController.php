<?php

namespace EmailSystemBundle\Controller;

use EmailSystemBundle\Entity\EmailSystem;
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
        $emailSystemRepo = $this->getDoctrine()->getRepository('EmailSystemBundle\Entity\EmailSystem');

        $emailSystemList = $emailSystemRepo->findAll([],[
            'label' => 'ASC'
        ]);

        return $this->render("EmailSystemBundle:EmailSystem:list.html.twig", [
            'emailList' => $emailSystemList
        ]);
    }

    /**
     * @Route("/setting/email/add", name="app_email_setting_add")
     * @Route("/setting/email/edit/{id}", name="app_email_setting_edit")
     */
    public function editAction(int $id = null)
    {
        $emailSystemRepo = $this->getDoctrine()->getRepository('EmailSystemBundle:EmailSystem');

        $emailSystem = new EmailSystem();
        if (null !== $id){
            $emailSystem = $emailSystemRepo->find($id);
        }

        $formHandler = $this->get('email_system.form.handler.email_system');
        $form = $formHandler->getForm($emailSystem);

        return $this->render("EmailSystemBundle:EmailSystem:edit.html.twig", [
            '$email' => $emailSystem,
            'form' => $form->createView()
        ]);
    }
}