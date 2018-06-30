<?php

namespace AppBundle\Service;

use Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Translation\Translator;

class SmsService
{
    private $turboSMSAuthData;

    private $logger;

    public function __construct($turboSMSAuthData, Logger $logger)
    {
       $this->turboSMSAuthData  = $turboSMSAuthData;

       $this->logger = $logger;
    }

    public function send($message, $phone)
    {
        $client = $this->auth();

        $sms = [
            'sender' => 'FindYou',
            'destination' => $phone,
            'text' => $message
        ];

        $result = $client->SendSMS($sms);
        $messageId = $result->SendSMSResult->ResultArray[1];

        $status = $client->GetMessageStatus(['MessageId' => $messageId]);

        $this->logger->info(sprintf('SMS for %s: "%s"', $phone, $status->GetMessageStatusResult));

        try {
            if ('Отправлено' != $status->GetMessageStatusResult) {
                throw new \Exception();
            }
        } catch (\Exception $exception) {

        }
    }

    private function auth()
    {
        $client = new SoapClient('http://turbosms.in.ua/api/wsdl.html', ['encoding' => ' UTF-8']);

        $client->Auth($this->turboSMSAuthData);

        return $client;
    }
}
