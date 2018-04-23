<?php

namespace Vidia\AuthBundle\Handler;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var \JMS\Serializer\Serializer
     */
    private $serializer;
    private $container;

    public function __construct(\JMS\Serializer\Serializer $serializer, Container $container)
    {
        $this->serializer = $serializer;
        $this->container = $container;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $response = new Response($this->serializer->serialize($token->getUser(), 'json'), 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->request->get('email') && $request->request->get('password')) {
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->container->get('doctrine')->getManager();
            /** @var \Vidia\AuthBundle\Entity\User $user */
            $user = $em
                ->getRepository('VidiaAuthBundle:UserInterface')
                ->findOneByEmail($request->request->get('email'))
            ;
            if ($user) {
                $encoder_service = $this->container->get('security.encoder_factory');
                $encoder = $encoder_service->getEncoder($user);
                if ($encoder->isPasswordValid($user->getPassword(), trim($request->request->get('password')), $user->getSalt())) {
                    if (1 != $user->getStatus()) {
                        return new Response(json_encode(['account' => 'Your account is locked']), 423, ['Content-Type' => 'application/json']);
                    }

                    $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
                    $this->container->get('security.context')->setToken($token);
                    $this->container->get('session')->set('_security_secured_area', serialize($token));

                    return $this->onAuthenticationSuccess($request, $token);
                }
            }
        }

        return new Response(null, 403);
    }
}
