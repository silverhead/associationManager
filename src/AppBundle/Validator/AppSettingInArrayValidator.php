<?php

namespace AppBundle\Validator;


use AppBundle\Manager\SettingManager;
use AppBundle\Validator\Constraint\AppSettingInArrayConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AppSettingInArrayValidator extends ConstraintValidator
{
    /**
     * @var SettingManager
     */
    private $settingManager;

    public function __construct(SettingManager $settingManager)
    {
        $this->settingManager = $settingManager;
    }

    public function validate($value, Constraint $constraint): bool
    {
        if (!$constraint instanceof AppSettingInArrayConstraint){
            throw new UnexpectedTypeException($constraint, AppSettingInArrayConstraint::class);
        }

        // null or empty is managed by another constraint if necessary!
        if (null === $value || '' === $value) {
            return true;
        }

        // only string is authorized
        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $code = $constraint->listCode;

        $multiple = $constraint->multiple;

        $list = $this->settingManager->getFormatedSettingValue($code);

        if (!$multiple && !in_array($value, $list)){
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->setParameter('%listCode%', $code)
                ->addViolation();
        }

        if ($multiple){
            $values = explode(',', $value);
            foreach ($values as $string) {
                if (!in_array($string, $list)) {
                    $this->context->buildViolation($constraint->message)
                        ->setParameter('%string%', $string)
                        ->setParameter('%listCode%', $code)
                        ->addViolation();
                }
            }
        }

        return true;

    }

}