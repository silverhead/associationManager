<?php

namespace MemberBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SendMailController extends Controller
{

    /**
     * @Route("member/member/send-late-payment-mail", name="member_member_send_late_payment_mail",  options = { "expose" = true })
     * @param Request $request
     * @throws \Exception
     */
    public function sendMailLatePaymentAction(Request $request)
    {
        $translator = $this->get('translator');

        $settingManager = $this->get('app.manager.setting');

        $subject = $settingManager->getSettingValue('member.email.late_member_notification_email_subject');
        $body = $settingManager->getSettingValue('member.email.late_member_notification_email_body');

        $delay = $settingManager->getSettingValue('subscription.delay.late_payment_member_email_sending');

        $feeManager = $this->get('member.manager.subscription_fee');

        $data = $feeManager->getRepository()->getLatePaymentFeeMemberListForSendingMail($delay);


        $memberIdSendMailSuccessList = array();

        if (count($data) > 0 ){
            $sendMailManager = $this->get('app.manager.send_mail');

            $placeholderArray = array(
                'gender' => 'gender',
                'lastName' => 'lastName',
                'firstName' => 'firstName',
                'subscriptionLabel' => 'subscriptionLabel',
                'startDate' => 'startDate',
                'endDate' => 'endDate',
                'totalCost' => 'cost'
            );

            $sendMailManager->prepareData(
                $placeholderArray,
                $subject,
                $body,
                'email/common.html.twig',
                $translator->trans('app.common.dateFormat'));

            foreach($data as $member){
                $nbSendingMail = $sendMailManager->send($member);

                if ($nbSendingMail > 0){
                    $memberIdSendMailSuccessList[] = $member['id'];
                }
            }

            if (count($memberIdSendMailSuccessList) > 0 ){
                $memberManager = $this->get('member.manager.member');
                $memberManager->saveLastDateSendingMailForLatePaymentMember($memberIdSendMailSuccessList);
            }
        }

        $array = array(
            'nbMailsSent' => count($memberIdSendMailSuccessList)
        );

        return new Response(
            (new Serializer(
                [new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json')
        );

    }

    /**
     * @Route("member/member/send-soon-fee-new-payment-mail", name="member_member_send_soon_fee_new_payment_mail",  options = { "expose" = true })
     * @param Request $request
     * @throws \Exception
     */
    public function sendMailSoonFeeNewPaymentAction()
    {
        $translator = $this->get('translator');

        $settingManager = $this->get('app.manager.setting');
        $delay = $settingManager->getSettingValue('subscription.delay.new_fee_coming_soon_email_sending');

        $subject    = $settingManager->getSettingValue('member.email.new_fee_coming_soon_email_subject');
        $body       = $settingManager->getSettingValue('member.email.new_fee_coming_soon_email_body');

        $now = new \DateTime();
        $delayDayMax = (clone $now)->add(new \DateInterval("P20D"));

        $feeRepo = $this->getDoctrine()->getRepository('MemberBundle:MemberSubscriptionFee');

        $data = $feeRepo->getSoonFeeNewPaymentMemberListForSendingEmail($now, $delayDayMax, $delay);

        $sendMailManager = $this->get('app.manager.send_mail');

        $placeholderArray = array(
            'gender' => 'gender',
            'lastName' => 'lastName',
            'firstName' => 'firstName',
            'subscriptionLabel' => 'subscriptionLabel',
            'startDate' => 'startDate',
            'endDate' => 'endDate',
            'totalCost' => 'cost'
        );

        $sendMailManager->prepareData(
            $placeholderArray,
            $subject,
            $body,
            'email/common.html.twig',
            $translator->trans('app.common.dateFormat')
        );

        $memberIdSendMailSuccessList = array();

        foreach($data as $member){
            $nbSendingMail = $sendMailManager->send($member);

            if ($nbSendingMail > 0){
                $memberIdSendMailSuccessList[] = $member['id'];
            }
        }

        if (count($memberIdSendMailSuccessList) > 0 ){
            $memberManager = $this->get('member.manager.member');
            $memberManager->saveLastDateSendingMailForSoonFeeNewPayment($memberIdSendMailSuccessList);
        }

        $array = array(
            'nbMailsSent' => count($memberIdSendMailSuccessList)
        );

        return new Response(
            (new Serializer(
                [new ObjectNormalizer()], [new JsonEncoder()]
            ))->serialize($array, 'json')
        );
    }
}
