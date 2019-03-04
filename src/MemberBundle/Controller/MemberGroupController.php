<?php

namespace MemberBundle\Controller;

use MemberBundle\Entity\MemberGroup;
use MemberBundle\Form\Type\MemberGroupFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        return new Response($this->memberGroupList($request));
    }

    public function memberGroupList(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $memberGroups = $em->getRepository('MemberBundle:MemberGroup')->findAll();

        return $this->renderView('member/groups/list.html.twig', array(
            'memberGroups' => $memberGroups,
        ));
    }

    /**
     * @Route("/member/group/add", name="member_group_add", options = { "expose" = true })
     * @Route("/member/group/edit/{id}", name="member_group_edit", options = { "expose" = true })
     * @Method("GET|POST")
     */
    public function editAction($id = null)
    {
        $memberGroup = new MemberGroup();
        if ($id > 0){
            $repo = $this->getDoctrine()->getRepository('MemberBundle:MemberGroup');
            $memberGroup = $repo->find($id);
        }

        $form = $this->createForm(MemberGroupFormType::class, $memberGroup);

        return $this->render('member/groups/modalForm.html.twig', array(
            'form' => $form->createView(),
            'memberGroup' => $memberGroup,
        ));
    }

    /**
     * @Route("/member/group/json/save/{id}", name="member_group_save_json", defaults={"id": null}, options={"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request, $id = null)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');

        $this->denyAccessUnlessGranted('MEMBER_GROUPS_EDIT', null, $translator->trans('app.common.access_denied'));

        $doctrine = $this->getDoctrine();

        $repo = $this->getDoctrine()->getRepository('MemberBundle:MemberGroup');
        $memberGroup = $repo->find($id);

        if(null === $memberGroup){
            $memberGroup = new MemberGroup();
        }

        $form = $this->createForm(MemberGroupFormType::class, $memberGroup);
        $form->handleRequest($request);

        $data = array(
            'code' => 'error',
            'message' => $translator->trans('member.group.form.error')
        );

        $message = 'member.group.form.error';

        if ($form->isSubmitted() && $form->isValid()){
            $memberGroup = $form->getData();

            $em = $doctrine->getManager();
            $em->persist($memberGroup);
            $em->flush();

            $data = array(
                'code' => 'success',
                'message' => $translator->trans('member.group.form.success')
            );
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("member/group/delete/{id}", name="member_group_delete", options={"expose" = true})
     * @param Request $request
     * @param $id
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new \BadMethodCallException("Only AJAX request supported!");
        }

        $translator = $this->get('translator');

        $doctrine = $this->getDoctrine();

        $repo = $doctrine->getRepository('MemberBundle:MemberGroup');

        $entity = $repo->find($id);

        if (null === $entity) {
            $array = [
                'code' => 'error',
                'message' => $translator->trans('member.groups.delete.deleteErrorMissingText'),
            ];

            return new Response(
                (new Serializer(
                    [new ObjectNormalizer()], [new JsonEncoder()]
                ))->serialize($array, 'json')
            );
        }

        try{
            $doctrine->getManager()->remove($entity);
            $doctrine->getManager()->flush();

            $array = [
                'code' => 'success',
                'message' => $translator->trans(
                    'member.groups.delete.deleteSuccessText',
                    ['%group%' => $entity->getLabel()]
                ),
            ];
        }
        catch (\Exception $ex){
            $array = [
                'code' => 'error',
                'message' => $translator->trans(
                    'app.common.errorComming',
                    [
                        '%error%' => '<br />'.$ex->getMessage(),
                    ]
                ),
            ];
        }

        return new Response(
            (new Serializer(
                [new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json')
        );
    }
}
