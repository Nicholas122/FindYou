<?php

namespace AppBundle\Service;

use AppBundle\Entity\RestorePassAccessKey;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Vidia\AuthBundle\Service\SessionService;

class PasswordService
{
    private $em;

    private $encoderFactory;

    private $sessionService;

    public function __construct( SessionService $sessionService ,EntityManagerInterface $entityManager, EncoderFactoryInterface $encoderFactory)
    {
        $this->em = $entityManager;

        $this->encoderFactory = $encoderFactory;

        $this->sessionService = $sessionService;
    }

    public function isPasswordValid(User $user, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        $response = $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());

        return $response;
    }

    public function generateAccessKey($phone)
    {
        $response = false;

        $repository = $this->em->getRepository('AppBundle:User');

        $user = $repository->findOneBy(['phone' => $phone]);

        if ($user instanceof User) {
            $accessKey = $this->generateRandomKey();

            $restorePassAccessKey = new RestorePassAccessKey();

            $restorePassAccessKey->setAccessKey($accessKey);
            $restorePassAccessKey->setUser($user);

            $this->removeOldRestorePassAccessKey($user);
            $this->em->persist($restorePassAccessKey);
            $this->em->flush();

            $this->sessionService->killAll($user);

            $response = $accessKey;
        }

        return $response;
    }

    public function removeOldAccessKey(User $user)
    {
        $repository = $this->em->getRepository('AppBundle:RestorePassAccessKey');
        $restorePassAccessKeys = $repository->findBy(['user' => $user->getId()]);

        foreach ($restorePassAccessKeys as $restorePassAccessKey) {
            $this->em->remove($restorePassAccessKey);
        }
    }

    public function restorePassword($password, $accessKey)
    {
        $response = false;

        $repository = $this->em->getRepository('AppBundle:RestorePassAccessKey');

        $restorePassAccessKey = $repository->findOneBy(['accessKey' => $accessKey]);

        if ($restorePassAccessKey instanceof RestorePassAccessKey) {
            $repository = $this->em->getRepository('AppBundle:User');

            $user = $repository->findOneById($restorePassAccessKey->getUser()->getId());
            $user->setPlainPassword($password);
            $this->em->persist($user);

            $this->removeOldRestorePassAccessKey($user);

            $response = true;
        }

        return $response;
    }

    private function generateRandomKey($length = 6)
    {
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= rand(0, 9);
        }

        return $randomString;
    }

    private function removeOldRestorePassAccessKey(User $user)
    {
        $repository = $this->em->getRepository('AppBundle:RestorePassAccessKey');

        $restorePassAccessKeys = $repository->findBy(['user' => $user->getId()]);

        foreach ($restorePassAccessKeys as $restorePassAccessKey) {
            $this->em->remove($restorePassAccessKey);
            $this->em->flush();
        }
    }
}
