<?php

namespace Vidia\AuthBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vidia\AuthBundle\Entity\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Vidia\AuthBundle\Service\ApiTokenService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserEntityListener
{
    /**
     * @var ApiTokenService
     */
    private $apiTokenService;

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    private $tokenStorage;

    /**
     * UserInterfaceEntityListener constructor.
     *
     * @param ApiTokenService     $apiTokenService
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(ApiTokenService $apiTokenService, UserPasswordEncoder $encoder, TokenStorageInterface $tokenStorage)
    {
        $this->apiTokenService = $apiTokenService;

        $this->encoder = $encoder;

        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Encode password when peresisting new user.
     *
     * @param UserInterface      $user
     * @param LifecycleEventArgs $args
     *
     * @ORM\PrePersist
     */
    public function encodePasswordOnPrePersist(UserInterface $user, LifecycleEventArgs $args)
    {
        $this->encodePassword($user);
    }

    /**
     * Generate api and refresh tokens on user creation.
     *
     * @param UserInterface      $user
     * @param LifecycleEventArgs $args
     *
     * @ORM\PostPersist
     */
    public function generateTokens(UserInterface $user, LifecycleEventArgs $args)
    {
        /**
         * @var EntityManager
         */
        $em = $args->getObjectManager();

        $this->apiTokenService->createSession($user, $em);
    }

    /**
     * Edit user role.
     *
     * @param UserInterface      $user
     * @param PreUpdateEventArgs $args
     *
     * @ORM\PreUpdate
     */
    public function editRole(UserInterface $user, PreUpdateEventArgs $args)
    {
        $changes = $args->getEntityChangeSet();
        $user = $this->tokenStorage->getToken()->getUser();

        if (array_key_exists('role', $changes) && 'ROLE_SUPER_ADMIN' != $user->getRole()) {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * Encode new password when updating a user.
     *
     * @param UserInterface      $user
     * @param PreUpdateEventArgs $args
     *
     * @ORM\PreUpdate
     */
    public function encodePasswordOnPreUpdate(UserInterface $user, LifecycleEventArgs $args)
    {
        $this->encodePassword($user);

        $om = $args->getObjectManager();
        $uow = $om->getUnitOfWork();
        $meta = $om->getClassMetadata(get_class($user));

        $uow->recomputeSingleEntityChangeSet($meta, $user);
    }

    /**
     * Encode the value of plainPassword property to password property.
     *
     * @param UserInterface $user
     */
    private function encodePassword(UserInterface $user)
    {
        if (null === $plainPassword = $user->getPlainPassword()) {
            return;
        }

        $encoded = $this->encoder->encodePassword(
            $user,
            $plainPassword
        );

        $user->setPassword($encoded);
    }
}
