<?php

namespace Vidia\AuthBundle\Security\Authentication;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Vidia\AuthBundle\Entity\Session;

class ApiKeyUserProvider implements UserProviderInterface
{
    /** @var EntityManager $em */
    protected $em;

    /** @var string $increaseTime */
    protected $increaseTime;

    protected $env;

    /**
     * ApiKeyUserProvider constructor.
     *
     * @param EntityManager $em
     * @param string        $increaseTime
     */
    public function __construct(EntityManagerInterface $em, $increaseTime, $env)
    {
        $this->em = $em;

        $this->increaseTime = $increaseTime;

        $this->env = $env;
    }

    /**
     * @param string $username
     *
     * @return null|object
     */
    public function loadUserByUsername($username)
    {
        /**
         * @var Session
         */
        $session = $this->em->getRepository('VidiaAuthBundle:Session')->findOneBy(['accessToken' => $username]);
        if (null === $session) {
            return null;
        }
        $user = $session->getUser();

        if (new \DateTime('now') < $session->getExpirationDate()) {
            $expirationDate = new \DateTime("+$this->increaseTime minutes");
            $session->setExpirationDate($expirationDate);
            $session->setOnline(new \DateTime('now'));

            if ('test' != $this->env) {
                $expire = new \DateTime('+15 days');

                setcookie('s_key', $session->getSessionKey(), $expire->getTimestamp(), '/');
                setcookie('language', $user->getLanguage()->getLocale(), $expire->getTimestamp(), '/');
            }
            $user->setExpirationDate($expirationDate);

            $this->em->persist($session);
            $this->em->flush();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return 'Vidia\AuthBundle\Entity\UserInterface' === $class;
    }
}
