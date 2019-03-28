<?php

namespace SubscriptionBundle\Manager;

use AppBundle\Manager\ImportManagerBase;
use Doctrine\ORM\EntityManager;
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
                    $subscriptionFeeImport->setPaymentCode($value);
                    break;
                case 8:
                    $subscriptionFeeImport->setPaymentDate($value);
                    break;
                case 9:
                    $subscriptionFeeImport->setComment($value);
                    break;
            }
        }
        return $subscriptionFeeImport;
    }

    protected function recordData()
    {

    }
}