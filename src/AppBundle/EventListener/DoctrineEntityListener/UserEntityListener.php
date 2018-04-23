<?php

namespace AppBundle\EventListener\DoctrineEntityListener;

use AppBundle\Entity\Language;
use AppBundle\Entity\Photo;
use AppBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\RequestStack;

class UserEntityListener
{
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Set user language.
     *
     * @ORM\PrePersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function setLanguage(User $entity, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $repository = $em->getRepository('AppBundle:Language');

        $request = $this->requestStack->getCurrentRequest();

        if ($request instanceof RequestStack) {
            $locale = $request->getPreferredLanguage();
            $language = $repository->findOneBy(['locale' => $locale]);
        }

        if (!isset($language) || !$language instanceof Language) {
            $language = $repository->findOneBy(['isDefault' => 1]);
        }

        $entity->setLanguage($language);
    }

    /**
     * Update user language.
     *
     * @ORM\PreUpdate()
     *
     * @param LifecycleEventArgs $args
     */
    public function updateLanguage(User $entity, PreUpdateEventArgs $args)
    {
        $changes = $args->getEntityChangeSet();

        if (array_key_exists('language', $changes)) {
            $expire = new \DateTime('+15 days');
            setcookie('language', $entity->getLanguage()->getLocale(), $expire->getTimestamp(), '/');
        }
    }

    /**
     * Update user language.
     *
     * @ORM\PostUpdate()
     *
     * @param LifecycleEventArgs $args
     */
    public function updatePhoto(User $entity, LifecycleEventArgs $args)
    {
        $changes = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($entity);
        $photo = $entity->getPhoto();

        if (array_key_exists('photo', $changes) && $photo instanceof Photo) {

            $this->removeOldPhoto($entity, $args);

            $em = $args->getEntityManager();

            $repository = $em->getRepository('AppBundle:Photo');

            /**
             * @var Photo $photo
             */
            $photo = $repository->findOneById($photo->getId());

            $photo->setIsUsed(1);

            $em->persist($photo);
            $em->flush();
        }
    }


    private function removeOldPhoto(User $entity, LifecycleEventArgs $args)
    {
        $changes = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($entity);


        if (array_key_exists('photo', $changes)) {
            $oldPhoto = $changes['photo'][0];

            if ($oldPhoto instanceof Photo) {
                $em = $args->getEntityManager();

                $repository = $em->getRepository('AppBundle:Photo');

                /**
                 * @var Photo $oldPhoto
                 */
                $oldPhoto = $repository->findOneById($oldPhoto->getId());

                $oldPhoto->setIsUsed(0);

                $em->persist($oldPhoto);
                $em->flush();
            }
        }
    }
}
