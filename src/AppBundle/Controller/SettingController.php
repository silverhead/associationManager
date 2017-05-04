<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SettingController extends Controller
{
    /**
     * @Route("/setting_manager", name="setting_manager")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ManagerAction(Request $request)
    {
        $formSettingAppHandler = $this->get('app.form.handler.setting_app');

        $formSettingAppHandler->setForm();

        if($formSettingAppHandler->process($request)){
            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('app.setting.form.saveSuccessText'));
        }

        return $this->render('setting/settingManager.html.twig', array(
            'menuSelect' => 'setting_manager',
            'formSetting' => $formSettingAppHandler->getForm()->createView()
        ));
    }
}
