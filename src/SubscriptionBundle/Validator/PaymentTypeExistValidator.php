<?php

namespace SubscriptionBundle\Validator;

use SubscriptionBundle\Validator\constraint\PaymentTypeExistConstraint;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PaymentTypeExistValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint): bool
    {
        if (!$constraint instanceof PaymentTypeExistConstraint){
            throw new UnexpectedTypeException($constraint, PaymentTypeExistConstraint::class);
        }

        // null or empty is managed by another constraint if necessary!
        if (null === $value || '' === $value) {
            return true;
        }

        // only string is authorized
        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $count = $this->entityManager->getRepository("SubscriptionBundle:SubscriptionPaymentType")->countByCode($value);

        if (0 === $count){
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }

        return true;
    }
}