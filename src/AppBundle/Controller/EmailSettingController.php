<?php

namespace AppBundle\Controller;

use AppBundle\Form\Model\EmailSettingModel;
use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EmailSettingController extends Controller
{
    public function indexAction(int $id)
    {
        $model = new EmailSettingModel();
        if ($id > 0){
            $repo = $this->getDoctrine()->getRepository('AppBundle:EmailSetting');
            $entity = $repo->find($id);

            $model->setEmailType($entity);
            $model->setBody($entity->getBody());
            $model->setSubject($entity->getSubject());
        }

        $formHandler = $this->get('app.form.handler.email_setting');
        $formHandler->setForm($model );

        return $this->render(':setting:emailSetting.html.twig', array(
            'form' => $formHandler->getForm()->createView()
        ));
    }

    /**
     * @Route("setting/email/save/{id}",name="app_email_setting_save", defaults={"id"=0})
     */
    public function saveAction(Request $request, int $id)
    {
        try {
            $translator = $this->get('translator');

            $formHandler = $this->get('app.form.handler.email_setting');
            $formHandler->setForm(new EmailSettingModel());

            if ($formHandler->process($request)) {
                $em = $this->getDoctrine()->getManager();

                $data = $formHandler->getData();

                $repo = $this->getDoctrine()->getRepository('AppBundle:EmailSetting');

                $model = $repo->find($id);
                $model->setSubject($data->getSubject());
                $model->setBody($data->getBody());

                $em->persist($model);
                $em->flush();

                $this->addFlash(
                    'success',
                    $translator->trans('app.setting.email.saveSuccess')
                );
            }
            else{
                $this->addFlash(
                    'error',
                    $translator->trans('app.setting.email.saveError')
                );
            }
        }
        catch (\Exception $ex){
            $this->addFlash(
                'error',
                $translator->trans(
                    'app.common.errorComming',
                    [
                        '%error%' => $ex->getCode()." : ".$ex->getMessage(),
                    ]
                )
            );
        }

        return $this->redirect(
            $this->generateUrl('app_email_setting', ['id' => $id])
        );
    }

    //
    /**
     * @Route("setting/email_show_json",name="app_email_setting_show_json", options={"expose" = true})
     */
    public function showJsonAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');

        //$this->denyAccessUnlessGranted('APP_EMAIL_SETTING', null, $translator->trans('app.common.access_denied'));

        $id = $request->get('id', null);

        $dataCallback = array();

        try{
            if (null === $id) {
                throw new HttpException(
                    "Arguments of the request are missing, you must send the \"id\" arguments for the call of this method!"
                );
            }

            $repo = $this->getDoctrine()->getRepository('AppBundle:EmailSetting');

            $model = $repo->find($id);

            if (null == $model){
                throw new DatabaseObjectNotFoundException(
                    "Not data found with the id \"" .$id. "\" "
                );
            }

            $dataCallback = array(
                'code' => 'success',
                'data' =>  $model
            );
        }
        catch (\Exception $ex){
            $dataCallback = [
                'code' => 'error',
                'message' => $translator->trans(
                    'app.common.errorComming',
                    [
                        '%error%' => $ex->getCode()." : ".$ex->getMessage(),
                    ]
                ),
            ];
        }

        return new Response(
            (new Serializer(
                [new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($dataCallback, 'json')
        );
    }
}