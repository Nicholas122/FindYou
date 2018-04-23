<?php

namespace AppBundle\Exception;


use AppBundle\Entity\UserExternalCharacteristic;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UserExternalCharacteristicException extends UnprocessableEntityHttpException
{
    private $externalCharacteristic;

    public function __construct(UserExternalCharacteristic $externalCharacteristic, ?string $message = null, \Exception $previous = null, int $code = 0)
    {
        $this->externalCharacteristic = $externalCharacteristic;

        parent::__construct($message, $previous, $code);
    }

    public function getExternalCharacteristic()
    {
        return $this->externalCharacteristic;
    }
}