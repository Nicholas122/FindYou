<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\ExternalCharacteristic;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidExternalCharacteristicValidator extends ConstraintValidator
{
    public function validate($entity, Constraint $constraint)
    {
        if (!$constraint instanceof IsValidExternalCharacteristic) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\IsValidExternalCharacteristic');
        }

        if (!$this->isValidType($entity)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

    private function isValidType(ExternalCharacteristic $entity)
    {
        $response = 'choice' === $entity->getType();

        return $response;
    }
}
