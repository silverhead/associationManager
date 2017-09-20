<?php

namespace UserBundle\Controller;

use AppBundle\Event\CredentialEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\GroupCredential;
use UserBundle\Form\Type\GroupCredentialType;

class UserGroupController extends Controller
{
    const ITEMS_PER_PAGE = 4;
    const PAGE_PARAMETER_NAME = 'pageTab3';

    /**
     * @Route("/user/group/list-part/{anchor}", name="user_group_list_part",  options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $anchor = null)
    {
        $page =  $request->get(self::PAGE_PARAMETER_NAME, 1);
        $currentRoute = 'members_manager';

        $userGroupManager = $this->get('user.manager.group');

        $results = $userGroupManager->paginatedList(
            $page,
            self::ITEMS_PER_PAGE,
            self::PAGE_PARAMETER_NAME,
            $anchor,
            $currentRoute
        );

        $pageH = $this->get('app.handler.page_historical');

        $pageH->setCallbackUrl('user_group_edit',
            $this->generateUrl($currentRoute),
            [self::PAGE_PARAMETER_NAME => $page],
            $anchor
        );

        return $this->render('/user/group/groupsList.html.twig', array(
            'results' => $results
        ));
    }

    /**
     * @Route("/user/group/add", name="user_group_add")
     * @Route("/user/group/edit/{id}", name="user_group_edit")
     * @param Request $request
     */
    public function editAction(Request $request, $id = null)
    {

        $credentialEvent = new CredentialEvent();
        $this->container->get('event_dispatcher')->dispatch(
            CredentialEvent::EVENT_NAME,
            $credentialEvent
        );

        $manager = $this->get('user.manager.group');
        $formHandler = $this->get('user.form.handler.group');

        $entity = $manager->find($id);
        if(null === $entity){
            $entity = $manager->getNewEntity();
        }

        $credentialsGroup = $entity->getCredentials();

        foreach($credentialEvent->getCredentialsList() as $credential){
            foreach($credential as $code => $labelCode){
                $entity->addCredential($code);
//                $curentCredential = $entity->getCredential($code);
//                //dump($curentCredential);
//                if(null === $curentCredential){
//                    $oCredential = new GroupCredential();
//                    $oCredential
//                        ->setUserGroup($entity)
//                        ->setCode($code);
//
//                    $entity->addCredential($oCredential);
//                }
            }
        }


        $formHandler->setForm($entity);

        if($formHandler->process($request)){

        }

//        $form = $formHandler->getForm();
//        $formCredentials = array();
////        foreach($entity->getCredentials() as $credential){
//        foreach($credentialEvent->getCredentialsList() as $credential){
//            foreach($credential as $code => $labelCode){
//                $oCredential = new GroupCredential($entity, $code);
//                $oCredential
////                    ->setUserGroup($entity)
////                    ->setCode($code)
//                    ->setActive(false);
//
//
//
//                $form->get('credentials')->add($oCredential);
//                $formCredentials[$credential->getCode()] = $credential->getActive();
//            }
//        }


//        $form->remove('credentials');

        return $this->render('/user/group/edit.html.twig', array(
            'formUserGroup' =>  $formHandler->getForm()->createView(),
            'credentials' => $credentialEvent->getCredentialsList(),
//            'formCredentials' => $formCredentials
        ));
    }


    /**
     * @Route("/user/group/json/save", name="user_group_save_json", options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()){
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');
        $statusManager = $this->get('user.manager.group');

        try{
            $statusManager->saveAjax($request);

            $array = [
                'code' => 'success',
                'message' => $translator->trans('user.group.edit.saveSuccessText')
            ];

        }
        catch(\Exception $e){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('app.common.errorComming', [
                    '%error%' => $e->getCode() . " : ".$e->getMessage()
                ])
            ];
        }

        return new Response(
            (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json'));
    }

    //
    /**
     * @Route("/user/group/delete/{id}", name="user_group_delete", options={"expose"=true})
     */
    public function deleteAction(Request $request, $id)
    {
        if(!$request->isXmlHttpRequest()){
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');
        $userGroupManager = $this->get('user.manager.group');

        $entity = $userGroupManager->find($id);

        if(null === $entity){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('user.group.delete.deleteErrorMissingText')
            ];

            return new Response(
                (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json'));
        }

        if(!$userGroupManager->delete($entity)){
            $array = [
                'code' => 'error',
                'message' => $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $userGroupManager->getErrors())
                ])
            ];

            return new Response(
                (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json'));
        }

        $array = [
            'code' => 'success',
            'message' => $translator->trans('user.group.delete.deleteSuccessText', ['%label%' => $entity->getLabel()])
        ];

        return new Response(
            (new Serializer([new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json'));
    }
}