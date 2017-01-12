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
    public function editMemberAction(Request $request, $id = 0)
    {
        return $this->render('member/memberEdit.html.twig');
    }

    /**
     * @Route("/member/fiche/{id}", name="member_fiche", requirements={"di": "\d+"})
     */
    public function ficheMemberAction(Request $request, $id)
    {
        return $this->render('member/memberEdit.html.twig');
    }

    /***
     * @Route("/members/view/{id}", name="member_view", requirements={"id": "\d+"});
     */
    public function viewMemberAction(Request $request, $id)
    {
        return $this->render('member/memberView.html.twig');
    }
}
