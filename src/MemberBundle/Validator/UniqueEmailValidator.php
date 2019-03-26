<?php

namespace MemberBundle\Validator;

use MemberBundle\Entity\MemberImport;
use MemberBundle\Validator\Constraint\UniqueEmailConstraint;
use Doctrine\ORM\EntityManager;
use MemberBundle\Entity\Member;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailValidator extends ConstraintValidator
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
        if (!$constraint instanceof UniqueEmailConstraint){
            throw new UnexpectedTypeException($constraint, UniqueEmailConstraint::class);
        }

        // null or empty is managed by another constraint if necessary!
        if (null === $value || '' === $value) {
            return true;
        }

        // only string is authorized
        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $entity = $this->context->getRoot();

        if (!$entity instanceof MemberImport){
            throw new UnexpectedTypeException($entity, MemberImport::class);
        }

        $countUniqueEmail = $this->entityManager->getRepository("MemberBundle:Member")->countUniqueEmail($value, $entity->getFirstName(), $entity->getLastName());

        if ($countUniqueEmail > 0){
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }

        return true;
    }

}