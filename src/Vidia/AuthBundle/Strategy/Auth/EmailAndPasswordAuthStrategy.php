<?php

namespace Vidia\AuthBundle\Strategy\Auth;

use Vidia\AuthBundle\Entity\UserInterface;
use Vidia\AuthBundle\Service\ApiTokenService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Doctrine\ORM\EntityManager;

class EmailAndPasswordAuthStrategy implements AuthStrategyInterface
{
    /** @var EntityManager $em */
    private $em;

    /** @var EncoderFactory $encoderFactory */
    private $encoderFactory;

    /** @var ApiTokenService $apiTokenService */
    private $apiTokenService;

    /**
     * EmailAndPasswordAuthStrategy constructor.
     *
     * @param EntityManager   $em
     * @param EncoderFactory  $encoderFactory
     * @param ApiTokenService $apiTokenService
     */
    public function __construct(
        EntityManager $em,
        EncoderFactory $encoderFactory,
        ApiTokenService $apiTokenService
    ) {
        $this->em = $em;
        $this->encoderFactory = $encoderFactory;
        $this->apiTokenService = $apiTokenService;
    }

    /**
     * {@inheritdoc}
     */
    public function authorize(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = $this->em
            ->getRepository(UserInterface::class)
            ->findOneByEmail($email)
        ;

        if (!$user instanceof UserInterface) {
            return false;
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        if (!$encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
            return false;
        }

        if (new \DateTime('now') >= $user->getExpirationDate()) {
            $user = $this->apiTokenService->createSession($user, $this->em);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        return null !== $email && null !== $password;
    }
}
