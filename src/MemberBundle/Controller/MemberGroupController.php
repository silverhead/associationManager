<?php

namespace MemberBundle\Controller;

use MemberBundle\Entity\MemberGroup;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Membergroup controller.
 *
 */
class MemberGroupController extends Controller
{
    public function __construct(ContainerInterface $container = null)
    {
        $this->setContainer($container);
    }

    /**
     * @Route("/member/groups/list-part/{anchor}", name="member_groups_list_part",  options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $memberGroups = $em->getRepository('MemberBundle:MemberGroup')->findAll();

        return $this->renderView('member/groups/list.html.twig', array(
            'memberGroups' => $memberGroups,
        ));
    }

    /**
     * Finds and displays a memberGroup entity.
     *
     * @Route("/show/{id}", name="member_group_show")
     * @Method("GET")
     */
    public function showAction(MemberGroup $memberGroup)
    {

        return $this->render('membergroup/show.html.twig', array(
            'memberGroup' => $memberGroup,
        ));
    }
}
