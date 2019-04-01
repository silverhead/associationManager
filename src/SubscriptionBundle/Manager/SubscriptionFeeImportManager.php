<?php

namespace SubscriptionBundle\Manager;

use AppBundle\Manager\ImportManagerBase;
use Doctrine\ORM\EntityManager;
use MemberBundle\Entity\MemberSubscriptionFee;
use MemberBundle\Entity\MemberSubscriptionHistorical;
use SubscriptionBundle\Entity\SubscriptionFeeImport;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionFeeImportManager extends ImportManagerBase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * SubscriptionFeeImportManager constructor.
     * @param EntityManager $entityManager
     * @param TranslatorInterface $translator
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManager $entityManager ,TranslatorInterface $translator, ValidatorInterface $validator)
    {
        parent::__construct($translator, $validator);
        $this->entityManager = $entityManager;
    }

    protected function getMinColumnLength(): int
    {
        return SubscriptionFeeImport::MIN_COLUMN_LENGTH;
    }

    protected function getDataLine(array $dataLine, int $numLine): SubscriptionFeeImport
    {
        $subscriptionFeeImport = new SubscriptionFeeImport();
        $subscriptionFeeImport->setNumLine($numLine);
        foreach ($dataLine as $i => $value) {
            $value = trim($value);
            switch ($i)
            {
                case 0:
                    $subscriptionFeeImport->setLastName($value);
                    break;
                case 1:
                    $subscriptionFeeImport->setFirstName($value);
                    break;
                case 2:
                    $subscriptionFeeImport->setEmail($value);
                    break;
                case 3:
                    $subscriptionFeeImport->setSubscriptionCode($value);
                    break;
                case 4:
                    $subscriptionFeeImport->setAmount( (float) $value );
                    break;
                case 5:
                    $subscriptionFeeImport->setStartDate($value);
                    break;
                case 6:
                    $subscriptionFeeImport->setEndDate($value);
                    break;
                case 7:
                    if ("" !== $value){
                        $subscriptionFeeImport->setPaymentCode($value);
                    }
                    break;
                case 8:
                    if ("" !== $value){
                        $subscriptionFeeImport->setPaymentDate($value);
                    }
                    break;
                case 9:
                    if ("" !== $value){
                        $subscriptionFeeImport->setComment($value);
                    }
                    break;
            }
        }
        return $subscriptionFeeImport;
    }

    protected function recordData()
    {
        $repoMember = $this->entityManager->getRepository("MemberBundle:Member");
        $repoSubscription = $this->entityManager->getRepository("SubscriptionBundle:Subscription");
        $repoMemberSubscription = $this->entityManager->getRepository("MemberBundle:MemberSubscriptionHistorical");
        $repoMemberSubscriptionFee = $this->entityManager->getRepository("MemberBundle:MemberSubscriptionFee");
        $repoPaymentType = $this->entityManager->getRepository("SubscriptionBundle:SubscriptionPaymentType");

        foreach ($this->data as $importDataLine) {
            $member = $repoMember->findOneBy(
                array(
                    'email' => $importDataLine->getEmail()
                )
            );

            $subscription = $repoSubscription->findOneBy(
                array(
                    'code' => $importDataLine->getSubscriptionCode()
                )
            );

            $memberSubscription = $repoMemberSubscription->findOneBy(array(
                'member' => $member,
                'subscription' => $subscription
            ));

            if (null === $memberSubscription){
                $memberSubscription = new MemberSubscriptionHistorical();
                $memberSubscription->setMember($member);
                $memberSubscription->setSubscription($subscription);
                $memberSubscription->setCost($subscription->getCost());
            }

            $startDate = \Datetime::createFromFormat('Y-m-d', $importDataLine->getStartDate());

            if (null === $memberSubscription->getStartDate() || $memberSubscription->getStartDate() > $startDate){
                $memberSubscription->setStartDate($startDate);
            }

            $endDate = \Datetime::createFromFormat('Y-m-d', $importDataLine->getEndDate());

            if (null === $memberSubscription->getEndDate() || $memberSubscription->getEndDate() < $startDate){
                $memberSubscription->setEndDate($endDate);
            }

            $memberSubscriptionFee = $repoMemberSubscriptionFee->findOneBy(array(
                'member' => $member,
                'subscription' => $subscription,
                'startDate' => $startDate,
                'endDate' => $endDate
            ));

            $importDataLine->setState(SubscriptionFeeImport::STATE_UPDATED);
            if(null === $memberSubscriptionFee)
            {
                $memberSubscriptionFee = new MemberSubscriptionFee();
                $memberSubscriptionFee->setMember($member);
                $memberSubscriptionFee->setSubscription($memberSubscription);
                $memberSubscriptionFee->setStartDate($startDate);
                $memberSubscriptionFee->setEndDate($endDate);
                $importDataLine->setState(SubscriptionFeeImport::STATE_CREATED);
            }

            $memberSubscriptionFee->setCost($importDataLine->getAmount());

            if (null !== $importDataLine->getPaymentCode()){
                $paymentType = $repoPaymentType->findOneBy(array('code' => $importDataLine->getPaymentCode()));
                $memberSubscriptionFee->setPayment($paymentType);

                if (null !== $importDataLine->getPaymentDate()){
                    $paymentDate = \Datetime::createFromFormat('Y-m-d', $importDataLine->getPaymentDate());
                    $memberSubscriptionFee->setPaymentDate($paymentDate);
                    $memberSubscriptionFee->setPaid(true);
                }
            }

            $memberSubscriptionFee->setNote($importDataLine->getComment());

            $this->entityManager->persist($memberSubscription);
            $this->entityManager->persist($memberSubscriptionFee);
            $this->entityManager->persist($member);

            $this->entityManager->flush();
        }
    }
}