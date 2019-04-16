<?php

namespace MemberBundle\Controller;

use AppBundle\QueryHelper\FilterQuery;
use AppBundle\QueryHelper\OrderQuery;
use MemberBundle\Entity\MemberSubscriptionHistorical;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MemberSubscriptionController extends Controller
{
    /**
     * @Route("/members/subscription/fees/list_part/{subHistId}/{anchor}", name="member_subscription_fees_list_part", requirements={"subHistId": "\d+"}, options = { "expose" = true });
     */
    public function feesListPartAction(Request $request, $subHistId)
    {
        $pageParamName = 'memberSubFeePage';
        $page = 1;

        $managerSubHistorical = $this->get('member.manager.subscription_historical');
        $subscriptionHistorical = $managerSubHistorical->find($subHistId);

        $manager = $this->get('member.manager.subscription_fee');

        $manager->addFilter(
            new FilterQuery("memberSubscriptionFee.subscription", $subscriptionHistorical),
            'subscription'
        )
        ->addOrder(
            new OrderQuery("memberSubscriptionFee.endDate", "DESC"),
            'feeEndDate'
        );

        $memberSubscriptionFees = $manager->paginatedList();

        $pageH = $this->get('app.handler.page_historical');

        $pageH->setCallbackUrl(
            'subscription_fee_save',
            $this->generateUrl('member_view', array(
                'id' => $subscriptionHistorical->getMember()->getId()
            )),
            [$pageParamName => $page],
            'subscriptionFees'
        );

        return $this->render('member/member/view/subscriptionFee.html.twig', array(
            'fees' => $memberSubscriptionFees
        ));
    }

    /**
     * @Route("/member/subscription/fee/edit/{id}", name="member_subscription_fees_edit", requirements={"id": "\d+"}, options= {"expose" = true});
     */
    public function editFeeAction($id)
    {
        $translator = $this->get('translator');

        if(!$this->isGranted("MEMBER_SUBSCRIPTION_FEE_SAVE")){
            $this->addFlash(
                'error',
                $translator->trans('app.common.access_denied')
            );

            return $this->render('main/flashMessage/flashMessageBlock.html.twig');
        }

        $manager = $this->get('member.manager.subscription_fee');
        $memberSubscriptionFee = $manager->find($id);

        $formFeeHandler = $this->get('member.form.handler.subscription_fee');
        $formFeeHandler->setForm($memberSubscriptionFee);

        return $this->render('member/member/subscriptionFee/edit.html.twig', array(
            'fee' => $memberSubscriptionFee,
            'form' => $formFeeHandler->getForm()->createView()
        ));
    }

    /**
     * @Route("/member/subscription/fee/save/{memberId}/{id}", name="member_subscription_fees_save", requirements={"memberId" : "\d+", "id": "\d+"}, options= {"expose" = true});
     */
    public function saveFeeAction(Request $request, $memberId, $id)
    {
        $translator = $this->get('translator');

        if(!$this->isGranted("MEMBER_SUBSCRIPTION_FEE_SAVE")){
            $this->addFlash(
                'error',
                $translator->trans('app.common.access_denied')
            );

            return $this->redirect(
                $this->generateUrl('member_view', ['id' => $memberId]).'#subscriptionFee'
            );
        }

        $manager = $this->get('member.manager.subscription_fee');
        $memberSubscriptionFee = $manager->find($id);

        $formHandler = $this->get('member.form.handler.subscription_fee');
        $formHandler->setForm($memberSubscriptionFee);

        if($formHandler->process($request)){
            $subscriptionFee = $formHandler->getData();

            $manager->save($subscriptionFee);

            $this->addFlash('success', $translator->trans('member.subscriptionFee.edit.saveSuccessText'));
        }
        else{
            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $manager->getErrors())
                ]));
        }

        $pageH = $this->get('app.handler.page_historical');

        $urlCallback = $pageH->getCallbackUrl('subscription_fee_save');

        return $this->redirect($urlCallback);
    }

    /**
     * @Route("/member/subscription/add/{memberId}", name="member_add_subscription", requirements={"memberId": "\d+"}, options= {"expose" = true});
     */
    public function addSubscriptionAction(Request $request, $memberId)
    {
        $translator = $this->get('translator');

        if(!$this->isGranted("MEMBER_SUBSCRIPTION_CREATE")){
            $this->addFlash(
                'error',
                $translator->trans('app.common.notAuthorizedPage'));

            return $this->redirect(
                $this->generateUrl('member_view', ['id' => $memberId]).'#subscription'
            );
        }

        $memberManager = $this->get("member.manager.member");
        $member = $memberManager->find($memberId);

        if(null == $member){
            $this->addFlash(
                'error',
                $translator->trans('member.member.memberNotFound'));

            return $this->redirect(
                $this->generateUrl('member_view', ['id' => $member->getId()]).'#subscription'
            );
        }

        $formHandler = $this->get('member.form.handler.subscription_historical');
        $formHandler->setForm(new MemberSubscriptionHistorical());

        if($formHandler->process($request)){
            $subscription = $formHandler->getData();

            $member->addSubscription($subscription);

            $memberManager->save($member);

            $this->addFlash('success', $translator->trans('member.subscription.edit.saveSuccessText'));
        }
        else{
            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $memberManager->getErrors())
                ]));
        }

        return $this->redirect(
            $this->generateUrl('member_view', ['id' => $member->getId()]).'#subscription'
        );
    }

    /**
     * @route("member/subscription/renew/{memberId}/{subscriptionId}", name="member_subscription_renew", options= {"expose" = true})
     */
    public function renewFeeAction(Request $request, $memberId, $subscriptionId)
    {
        return $this->renderView('subscription/subscription/fee/editModal.html.twig');
    }

//    /**
//     * @route("member/subscription/sendLatePaymentMail/{id}", name="member_subscription_send_late_payment_mail", requirements={"id": "\d+"}, options= {"expose" = true})
//     */
//    public function sendLatePaymentAction($id)
//    {
//        $translator = $this->get('translator');
//
//        if(!$this->isGranted("MEMBER_SUBSCRIPTION_VIEW")){
//            $this->addFlash(
//                'error',
//                $translator->trans('app.common.notAuthorizedPage'));
//
//            return $this->redirect(
//                $this->generateUrl('member_view', ['id' => $memberId]).'#subscription'
//            );
//        }
//
//
//        $associationName = $this->settingManager->getSettingValue("app.setting.association_name");
//
//        $latePaymentEmailSubject = $this->settingManager->getSettingValue("member.email.late_payment_subject");
//        $latePaymentEmailBody = $this->settingManager->getSettingValue("member.email.late_payment_body");
//
//        $keywordsToReplace = array(
//            '{gender}',
//            '{lastName}',
//            '{firstName}',
//            '{associationName}'
//        );
//
//        $data = array(
//            $entity->getGender(),
//            $entity->getLastName(),
//            $entity->getFirstName(),
//            $associationName
//        );
//
//        $latePaymentEmailSubject = str_replace('{associationName}', $associationName, $latePaymentEmailSubject);
//        $latePaymentEmailBody = str_replace($keywordsToReplace, $data, $latePaymentEmailBody);
//
//        $this->mailerTemplating->send(
//            ':member/email:latePayment.html.twig',
//            ['bodyContent' => nl2br($latePaymentEmailBody)],
//            $latePaymentEmailSubject,
//            $this->robotEmail,
//            $entity->getEmail()
//        );
//
//    }
}
