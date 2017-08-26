<?php

namespace MemberBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends Controller
{
    /**
     * @Route("/members/manager", name="members_manager")
     */
    public function indexAction()
    {
        return $this->render('member/membersManager.html.twig', array(
            'menuSelect' => 'members_manager'
        ));
    }

    /**
     * @Route("/members/edit/{id}", name="member_edit", requirements={"id": "\d+"}, defaults={"id": 0});
     */
    public function editMemberAction(Request $request, $id = 0)
    {
        // in stand by for entity member
        //        $voter = $this->get('member_member_voter');
        //
        //        $token = $this->get('security.token_storage')->getToken();
        //
        //        if($voter->vote($token))

        return $this->render('member/memberEdit.html.twig', [
            'menuSelect' => 'members_manager'
        ]);
    }

    /**
     * @Route("/members/view/{id}", name="member_view", requirements={"id": "\d+"});
     */
    public function viewMemberAction(Request $request, $id)
    {
        $member = $this->getMemberInfos();
        return $this->render('member/memberView.html.twig', [
            'member' => $member,
            'menuSelect' => 'member_manager'
        ]);
    }


    private function getMemberInfos()
    {
        return (object) [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'birthday' => \DateTime::createFromFormat('Y-m-d', '1981-05-07' ),
            'avatar' => 'user.png',
            'contactInformation' => (object) [
                'country' => 'France',
                'address' => '1 rue jean bonbeur',
                'postalCode' => '84000',
                'city' => 'Avignon',
                'phone' => '0123456789',
                'mobile' => '0623456789',
            ],
            'connection' => (object) [
                'pseudo' => 'johnDoe',
                'email' => 'johndoe@email.com',
                'group' => 'Utilisateur',
                'active' => 'Oui'
            ],
            'infosMember' => (object) [
                'status' => 'Membre premium',
                'subscription' => 'Abonnement 1 an premium',
                'periodicityPayment' => '1 an',
                'specialities' => [
                    'Electronique',
                    'Informatique',
                    'Travail du bois',
                ],
                'profession' => 'Développeur',
                'birthday' => \DateTime::createFromFormat('Y-m-d', '1981-05-07' ),
                'lastSubscriptionDate' => \DateTime::createFromFormat('Y-m-d', '2016-05-07' )
            ]
        ];
    }
}
