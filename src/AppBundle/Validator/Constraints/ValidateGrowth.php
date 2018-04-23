<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class ValidateGrowth extends Constraint
{
    public $message = 'The minimum possible growth can be 1 meter and maximum up to 2.5 meters.';
}
