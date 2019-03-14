<?php

namespace MemberBundle\Controller;

use MemberBundle\Form\Model\MemberImportModel;
use MemberBundle\Form\Type\MemberImportFormType;
use MemberBundle\Manager\MemberImportManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MemberImportController  extends Controller
{
    /**
     * @Route("members/import", name="member_import")
     */
    public function importAction(Request $request)
    {
        $root = $this->getParameter('kernel.root_dir');

        $pathImport = $root.DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR."import";

        $translator = $this->get('translator');

        $form = $this->createForm(MemberImportFormType::class, new MemberImportModel() );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $model = $form->getData();

            $model->getFile()->move($pathImport, $model->getFile()->getFilename());
            $pathFile = $pathImport.DIRECTORY_SEPARATOR.$model->getFile()->getFilename();

            $settingManager = $this->get('app.manager.setting');

            $memberImportManager = new MemberImportManager(
                $this->getDoctrine()->getManager(),
                $translator,
                $this->get('validator'),
                $settingManager,
                $this->get('security.password_encoder')
            );

            $memberImports = array();
            if ($memberImportManager->import($pathFile)){
                $memberImports = $memberImportManager->getData();
            }
            else{
                $errorMessage = "";

                foreach($memberImportManager->getErrors() as $error){
                    $errorMessage .= $errorMessage!=''?'<br>':'';
                    $errorMessage .= $error;
                }

                $this->addFlash('error', $errorMessage);
            }
        }

        $breadcrumbs = [
            [
                'href' => $this->redirectToRoute('dashboard'),
                'title' => $translator->trans('app.dashboard.callback'),
                'label' => $translator->trans('app.dashboard.title')
            ],
            [
                'href' =>  $this->generateUrl('members_manager').'#members',
                'title' => $translator->trans('member.manager.tabMembers'),
                'label' => $translator->trans('member.manager.tabMembers')
            ],
            ['label' => $translator->trans('member.member.import.title')]
        ];

        return $this->render('member/member/import.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'memberImports' => $memberImports,
            'form' => $form->createView()
        ]);
    }
}