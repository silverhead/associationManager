<?php

namespace SubscriptionBundle\Validator\constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class SubscriptionExistConstraint extends Constraint
{
    public $message = 'The subscription "%string%" has not found!';

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function validatedBy()
    {
        return 'subscription_fee.validator.import.subscription_exist';
    }
}