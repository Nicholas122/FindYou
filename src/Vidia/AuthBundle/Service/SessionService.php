<?php
/**
 * Created by PhpStorm.
 * User: nicholas
 * Date: 20.11.17
 * Time: 23:39.
 */

namespace Vidia\AuthBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Vidia\AuthBundle\Entity\Session;
use Vidia\AuthBundle\Entity\User;
use Vidia\AuthBundle\Entity\UserInterface;
use Vidia\AuthBundle\Repository\SessionRepository;

class SessionService
{
    private $em;

    private $requestStack;

    public function __construct(EntityManager $entityManager, RequestStack $requestStack)
    {
        $this->em = $entityManager;

        $this->requestStack = $requestStack;
    }

    public function killCurrent(UserInterface $user)
    {
        $token = $this->getToken();

        /**
         * @var SessionRepository
         */
        $repository = $this->em->getRepository('VidiaAuthBundle:Session');

        $repository->killCurrent($user, $token);
    }

    public function killAll(UserInterface $user)
    {
        $token = $this->getToken();

        /**
         * @var SessionRepository
         */
        $repository = $this->em->getRepository('VidiaAuthBundle:Session');

        $repository->killAllExceptCurrent($user, $token);
    }

    private function getToken()
    {
        $request = $this->requestStack->getCurrentRequest();

        $token = $request->headers->get('authorization');
        $token = explode(' ', $token);
        $token = ('Bearer' == $token[0] && count($token) > 1) ? $token[1] : $token[0];

        return $token;
    }
}
