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
    public function listAction(Request $request,string $anchor = null)
    {
        return new Response($this->memberGroupList($request, $anchor));
    }

    public function memberGroupList(Request $request, $anchor)
    {
        $memberGroupManager = $this->get('member.manager.group');

        $memberGroups = $memberGroupManager->paginatedList(
            $request->query->getInt('pageMemberGroup', 1),
            10,
            'pageMemberGroup',
            $anchor,
            $request->get('master_route', 'members_manager')
        );

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
        $translator = $this->get('translator');

        $this->denyAccessUnlessGranted('MEMBER_GROUPS_EDIT', null, $translator->trans('app.common.access_denied'));

        $memberGroupManager = $this->get('member.manager.group');

        $memberGroup = $memberGroupManager->getNewEntity();
        if ($id > 0){
            $memberGroup = $memberGroupManager->find($id);
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

        $memberGroupManager = $this->get('member.manager.group');

        $memberGroup = $memberGroupManager->find($id);

        if(null === $memberGroup){
            $memberGroup = $memberGroupManager->getNewEntity();
        }

        $form = $this->createForm(MemberGroupFormType::class, $memberGroup);
        $form->handleRequest($request);

        $data = array(
            'code' => 'error',
            'message' => $translator->trans('member.groups.form.error')
        );

        if ($form->isSubmitted() && $form->isValid()){
            $memberGroup = $form->getData();

            if ($memberGroupManager->save($memberGroup)){
                $data = array(
                    'code' => 'success',
                    'message' => $translator->trans('member.groups.form.success')
                );
            }
            else{
                $messageError = join("<br />>", $memberGroupManager->getErrors());

                $data = array(
                    'code' => 'error',
                    'message' => $translator->trans('member.groups.form.error') . ' : <br />'.$messageError
                );
            }
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

        $this->denyAccessUnlessGranted('MEMBER_GROUPS_DELETE', null, $translator->trans('app.common.access_denied'));

        $memberGroupManager = $this->get('member.manager.group');

        $entity = $memberGroupManager->find($id);

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

        if ($memberGroupManager->delete($entity)){
            $array = [
                'code' => 'success',
                'message' => $translator->trans(
                    'member.groups.delete.deleteSuccessText',
                    ['%group%' => $entity->getLabel()]
                ),
            ];
        }
        else{
            $messageError = join("<br />", $memberGroupManager->getErrors());

            $array = [
                'code' => 'error',
                'message' => $translator->trans(
                    'app.common.errorComming',
                    [
                        '%error%' => '<br />'.$messageError,
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
