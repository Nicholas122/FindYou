<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\ExternalCharacteristic;
use AppBundle\Entity\UserExternalCharacteristic;
use AppBundle\Exception\UserExternalCharacteristicException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidateGrowthValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ValidateGrowth) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ValidateGrowth');
        }

        if ($this->isGrowthInvalid($value)) {
            throw  new UserExternalCharacteristicException($this->context->getObject(), $constraint->message);
        }
    }

    private function isGrowthInvalid($value)
    {
        $response = false;

        $min = 100;
        $max = 250;

        /**
         * @var UserExternalCharacteristic $obj
         */
        $obj = $this->context->getObject();

        if ($obj->getExternalCharacteristic()->getName() === 'growth') {
            $response = $value < $min || $value > $max;
        }

        return $response;
    }
}
