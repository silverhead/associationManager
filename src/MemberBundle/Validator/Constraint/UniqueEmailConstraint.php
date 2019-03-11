<?php

namespace MemberBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class UniqueEmailConstraint extends Constraint
{
    public $message = 'The email "%string%" is already use for another member!';

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function validatedBy()
    {
        return 'member.validator.import.unique_email';
    }
}