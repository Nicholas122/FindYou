<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Language;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vidia\AuthBundle\Entity\Session;

class LocaleListener implements EventSubscriberInterface
{
    private $defaultLocale;

    private $tokenStorage;

    private $entityManager;

    public function __construct(ContainerInterface $container, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->defaultLocale = $container->getParameter('kernel.default_locale');

        $this->tokenStorage = $tokenStorage;

        $this->entityManager = $entityManager;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $token = $request->headers->get('authorization');
        $user = $this->getUser($token);
        $locale = $request->cookies->get('language');

        if (empty($locale)) {
            $locale = $request->getPreferredLanguage();
        }

        if (isset($token) && $user instanceof User) {
            $request->setLocale($user->getLanguage()->getLocale());
        } else {
            $locale = explode('|', str_replace(['_', '-'], '|', $locale))[0];
            $repository = $this->entityManager->getRepository('AppBundle:Language');
            $language = $repository->findOneBy(['locale' => $locale]);

            if (!$language instanceof Language) {
                $language = $repository->findOneBy(['isDefault' => 1]);
            }

            $request->setLocale($language->getLocale());
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered after the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 15)),
        );
    }

    private function getUser($token)
    {
        $response = null;
        $repository = $this->entityManager->getRepository('VidiaAuthBundle:Session');
        $token = trim(str_ireplace('bearer', '', $token));
        $session = $repository->findOneBy(['accessToken' => $token]);

        if ($session instanceof Session && $session->getExpirationDate() > new \DateTime('now')) {
            $response = $session->getUser();
        }

        return $response;
    }
}
