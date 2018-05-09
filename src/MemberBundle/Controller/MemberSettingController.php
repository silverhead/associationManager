<?php

namespace MemberBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class MemberSettingController extends Controller
{
    public function __construct(ContainerInterface $container = null)
    {
        $this->setContainer($container);
    }

    /**
     * @Route("/member/setting", name="member_setting")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function settingAction(Request $request)
    {
        $translator = $this->get('translator');

        if(!$this->isGranted("MEMBER_SETTING_EDIT")){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));

            return $this->redirect(
                $this->generateUrl('dashboard')
            );
        }

        $formSettingAppHandler = $this->get('member.form.handler.member_setting');

        $formSettingAppHandler->setForm();

        if($formSettingAppHandler->process($request)){
            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('member.member.setting.form.saveSuccessText'));
        }

        return $this->renderView(':member/member/setting:setting.html.twig', array(
            'form' => $formSettingAppHandler->getForm()->createView()
        ));
    }
}
