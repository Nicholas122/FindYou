<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class IsValidExternalCharacteristic extends Constraint
{
    public $message = 'This external characteristic is not of type choice.';
}
