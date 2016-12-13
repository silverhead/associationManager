<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends Controller
{
    /**
     * @Route("/members/manager", name="members_manager")
     */
    public function indexAction($name)
    {
        return $this->render('member/membersManager.html.twig');
    }

    /**
     * @Route("/members/edit/{id}", name="member_edit", requirements={"id": "\d+"}, defaults={"id": 0});
     */
    public function editMemberAction(Request $request, $id)
    {
        return $this->render('member/memberEdit.html.twig');
    }
}
