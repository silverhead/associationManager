<?php

namespace AppBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class AppSettingInArrayConstraint extends Constraint
{
    public $message = 'The string "%string%" is not in the list "%listCode%"';

    public $listCode;

    public $multiple = false;

    public function __construct($options = null)
    {
        if (null !== $options && !\is_array($options)) {
            $options = array(
                'listCode' => $options
            );
        }

        parent::__construct($options);

        if (null === $this->listCode) {
            throw new MissingOptionsException(sprintf('Option "listCode" must be given for constraint %s', __CLASS__), array('listCode'));
        }
    }

    public function validatedBy()
    {
        return 'app.validator.app_setting.in_array';
    }
}