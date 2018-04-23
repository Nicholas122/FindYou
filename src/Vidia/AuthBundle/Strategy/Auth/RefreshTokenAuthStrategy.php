<?php

namespace Vidia\AuthBundle\Strategy\Auth;

use Vidia\AuthBundle\Entity\Session;
use Vidia\AuthBundle\Service\ApiTokenService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class RefreshTokenAuthStrategy implements AuthStrategyInterface
{
    /** @var EntityManager $em */
    private $em;

    /** @var ApiTokenService $apiTokenService */
    private $apiTokenService;

    /**
     * RefreshTokenAuthStrategy constructor.
     *
     * @param EntityManager   $em
     * @param ApiTokenService $apiTokenService
     */
    public function __construct(
        EntityManager $em,
        ApiTokenService $apiTokenService
    ) {
        $this->em = $em;
        $this->apiTokenService = $apiTokenService;
    }

    /**
     * {@inheritdoc}
     */
    public function authorize(Request $request)
    {
        $refreshToken = $request->request->get('refreshToken');
        $oldToken = $request->request->get('accessToken');

        $session = $this->em
            ->getRepository('VidiaAuthBundle:Session')
            ->findOneBy(array(
                'refreshToken' => $refreshToken,
                'accessToken' => $oldToken,
            ));

        if (!$session instanceof Session) {
            return false;
        }

        $user = $this->apiTokenService->createSession($session->getUser(), $this->em);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        $refreshToken = $request->request->get('refreshToken');
        $oldToken = $request->request->get('accessToken');

        return null !== $refreshToken && null !== $oldToken;
    }
}
