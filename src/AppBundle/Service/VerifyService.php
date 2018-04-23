<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Entity\VerifyCode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Vidia\AuthBundle\Entity\Session;

class VerifyService
{
    private $em;

    private $requestStack;

    private $smsService;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, SmsService $smsService)
    {
        $this->em = $entityManager;

        $this->requestStack = $requestStack;

        $this->smsService = $smsService;
    }

    public function sendVerifyCode(User $user)
    {
        $code = $this->genCode();

        $verifyCode = new VerifyCode();

        $verifyCode->setCode($code);
        $verifyCode->setUser($user);

        $this->em->persist($verifyCode);

        $this->em->flush();

        $this->smsService->send($verifyCode->getCode(), $verifyCode->getUser()->getPhone());
    }

    public function verify($code)
    {
        $verifyCode = $this->findVerifyCode($code);
        $response = [
            'status' => 'FAIL',
            'data' => [
                'message' => 'The verify code is invalid',
            ],
        ];

        if ($verifyCode instanceof VerifyCode) {
            $sKey = $this->requestStack->getCurrentRequest()->cookies->get('s_key');
            $repository = $this->em->getRepository('VidiaAuthBundle:Session');
            $user = $verifyCode->getUser();
            /**
             * @var Session
             */
            $session = $repository->findOneBy(['sessionKey' => $sKey]);

            $user->setIsVerified(1);
            $this->em->remove($verifyCode);

            $this->em->persist($user);
            $this->em->flush();

            $user->setAccessToken($session->getAccessToken());
            $user->setRefreshToken($session->getRefreshToken());
            $user->setExpirationDate($session->getExpirationDate());

            $response = $user;
        }

        return $response;
    }

    private function genCode()
    {
        $code = mt_rand(1000, 9999);

        while (!$this->isCodeUnique($code)) {
            $code = mt_rand(1000, 9999);
        }

        return $code;
    }

    private function isCodeUnique($code)
    {
        $response = !boolval($this->findVerifyCode($code));

        return $response;
    }

    private function findVerifyCode($code)
    {
        $repository = $this->em->getRepository('AppBundle:VerifyCode');

        $verifyCode = $repository->findOneBy(['code' => $code]);

        return $verifyCode;
    }
}
