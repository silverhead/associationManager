<?php

namespace MemberBundle\Validator;

use MemberBundle\Validator\Constraint\GroupExistConstraint;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class GroupExistValidator extends ConstraintValidator
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
        if (!$constraint instanceof GroupExistConstraint){
            throw new UnexpectedTypeException($constraint, GroupExistConstraint::class);
        }

        // null or empty is managed by another constraint if necessary!
        if (null === $value || '' === $value) {
            return true;
        }

        // only string is authorized
        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $labels = explode(",", $value);

        $count = $this->entityManager->getRepository("MemberBundle:MemberGroup")->countByLabels($labels);

        if (count($labels)  != $count){
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }

        return true;
    }

}