<?php


namespace AppBundle\EventListener\KernelEventListener;


use AppBundle\Exception\UserExternalCharacteristicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class UserExternalCharacteristicExceptionListener
{
    private $translator;

    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if($exception instanceof UserExternalCharacteristicException)
        {
            $response = new Response($this->getResponseContent($exception));

            $event->setResponse($response);
        }
    }

    private function getResponseContent(UserExternalCharacteristicException $exception)
    {
        $message = $this->translator->trans($exception->getMessage(), [], 'validators');

        $content = ['error_type' => 'user_external_characteristic_error', 'errors' => [
            'value' => ['message' => $message]],
            'characteristic' => $exception->getExternalCharacteristic()->getExternalCharacteristic()->getName()
        ];

        return json_encode($content);
    }
}