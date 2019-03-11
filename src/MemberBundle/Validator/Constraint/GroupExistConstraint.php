<?php

namespace MemberBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class GroupExistConstraint extends Constraint
{
    public $message = 'The group "%string%" has not found!';

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function validatedBy()
    {
        return 'member.validator.import.group_exist';
    }
}