<?php

namespace SubscriptionBundle\Validator;

use SubscriptionBundle\Validator\constraint\SubscriptionExistConstraint;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SubscriptionExistValidator extends ConstraintValidator
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
        if (!$constraint instanceof SubscriptionExistConstraint){
            throw new UnexpectedTypeException($constraint, SubscriptionExistConstraint::class);
        }

        // null or empty is managed by another constraint if necessary!
        if (null === $value || '' === $value) {
            return true;
        }

        // only string is authorized
        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $count = $this->entityManager->getRepository("SubscriptionBundle:Subscription")->countByCode($value);

        if (0 === $count){
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }

        return true;
    }
}