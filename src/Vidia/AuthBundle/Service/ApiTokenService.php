<?php

namespace Vidia\AuthBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Vidia\AuthBundle\Entity\Session;
use Vidia\AuthBundle\Entity\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ApiTokenService
{
    /** @var UserPasswordEncoder $encoder */
    private $encoder;

    private $expirationDate;

    private $requestStack;

    private $env;

    /**
     * ApiTokenService constructor.
     *
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(UserPasswordEncoder $encoder, $expirationDate, RequestStack $requestStack, $env)
    {
        $this->encoder = $encoder;

        $this->expirationDate = $expirationDate;

        $this->requestStack = $requestStack;

        $this->env = $env;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function createSession(UserInterface $user, EntityManager $entityManager)
    {
        $accessToken = $this->generateAccessToken($user);
        $refreshToken = $this->generateRefreshToken($user);
        $expirationDate = new \DateTime("+$this->expirationDate minutes");

        $request = $this->requestStack->getCurrentRequest();

        if ($request instanceof Request && 'test' != $this->env) {
            $sessionKey = $request->cookies->get('s_key');

            $session = $this->getSession(['sessionKey' => $sessionKey], $entityManager);

            if (!$session instanceof Session) {
                $session = new  Session();
                $sessionKey = sha1(uniqid());
            }

            $session->setUser($user);
            $session->setIp($request->getClientIp());
            $session->setOs($this->getOS($request->server->get('HTTP_USER_AGENT')));
            $session->setAccessToken($accessToken);
            $session->setRefreshToken($refreshToken);
            $session->setExpirationDate($expirationDate);
            $session->setSessionKey($sessionKey);
            $session->setOnline(new \DateTime('now'));

            $expire = new \DateTime('+15 days');

            setcookie('s_key', $sessionKey, $expire->getTimestamp(), '/');
            setcookie('language', $user->getLanguage()->getLocale(), $expire->getTimestamp(), '/');

            $user->setAccessToken($accessToken);
            $user->setRefreshToken($refreshToken);
            $user->setExpirationDate($expirationDate);
            $entityManager->persist($session);
            $entityManager->flush();
        }

        return $user;
    }

    private function getSession($criteria, EntityManager $entityManager)
    {
        $repository = $entityManager->getRepository('VidiaAuthBundle:Session');

        $session = $repository->findOneBy($criteria);

        return $session;
    }

    private function generateAccessToken(UserInterface $user)
    {
        $accessToken = sha1(
            $this->encoder->encodePassword(
                $user,
                openssl_random_pseudo_bytes(4).date('Y-m-d H:i:s')
            )
        );

        return $accessToken;
    }

    /**
     * @param UserInterface $user
     *
     * @throws AuthenticationException
     *
     * @return UserInterface
     */
    private function generateRefreshToken(UserInterface $user)
    {
        $uniqueField = $user->getUniqueField();
        if (null === $uniqueField) {
            throw new AuthenticationException('No unique field');
        }

        $refreshToken = sha1(
            $this->encoder->encodePassword(
                $user,
                $uniqueField.date('Y-m-d H:i:s')
            )
        );

        return $refreshToken;
    }

    private function getOS($userAgent)
    {
        $osPlatform = 'Unknown OS Platform';

        $osArray = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
        );

        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
            }
        }

        return $osPlatform;
    }
}
