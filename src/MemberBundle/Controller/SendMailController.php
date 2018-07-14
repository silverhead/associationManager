<?php

namespace MemberBundle\Controller;

use AppBundle\QueryHelper\FilterQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SendMailController extends Controller
{
    /**
     * @param Request $request
     * @throws \Exception
     */
    public function SendMailLatePaymentAction(Request $request)
    {
        $settingManager = $this->get('app.manager.setting');

        $delay = $settingManager->getSettingValue('subscription.delay.late_payment_member_email_sending');

        $feeManager = $this->get('member.manager.subscription_fee');
        $memberManager = $this->get('member.manager.member');
        $sendMailLatePaymentManager = $this->get('member.manager.send_mail_late_payment');

        $membersFeeIdList = $feeManager->getLatePaymentFeeMemberIdList();

        if(count($membersFeeIdList) == 0){
            $membersFeeIdList[] = 0;
        }

        $memberManager->addFilter(
            new FilterQuery('m.id', $membersFeeIdList, FilterQuery::OPERATOR_IN)
        );

        $lastSendLimitDate = new \DateTime();
        $lastSendLimitDate->sub("P".$delay . "D");

        $memberManager->addFilter(
            new FilterQuery('m.lastSendingComingSoonFeeEmailDate', $lastSendLimitDate, '<=')
        );

        $memberList = $memberManager->getArrayList(0, 1000);

        foreach ($memberList as $member){
            $sendMailLatePaymentManager->send($member);
        }

    }
}
