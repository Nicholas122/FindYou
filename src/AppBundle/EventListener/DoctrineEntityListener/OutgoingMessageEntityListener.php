<?php

namespace AppBundle\EventListener\DoctrineEntityListener;

use AppBundle\Entity\IncomingMessage;
use AppBundle\Entity\OutgoingMessage;
use AppBundle\Entity\Photo;
use AppBundle\Entity\UserConversation;
use AppBundle\Service\Google\GoogleGeocodeService;
use AppBundle\Service\PhotoService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class OutgoingMessageEntityListener
{

    /**
     * Set author.
     *
     * @ORM\PrePersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function setAuthor(OutgoingMessage $entity, LifecycleEventArgs $args)
    {
        $entity->setAuthor($entity->getUser());
    }

    /**
     * Set data from conversation.
     *
     * @ORM\PrePersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function setDataFromConversation(OutgoingMessage $entity, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();

        $repository = $em->getRepository('AppBundle:UserConversation');

        $conversation = $entity->getConversation();

        $userConversations = $repository->findBy(['parentConversation' => $conversation->getId()]);

        /**
         * @var UserConversation $userConversation
         */
        foreach ($userConversations as $userConversation) {
            if ($userConversation->getUser()->getId() != $entity->getUser()->getId()) {
                $entity->setReceiver($userConversation->getUser());

                break;
            }
        }
    }

    /**
     * Create IncomingMessage.
     *
     * @ORM\PrePersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function createIncomingMessage(OutgoingMessage $entity, LifecycleEventArgs $args)
    {
        $incomingMessage = new IncomingMessage();
        $incomingMessage->setUser($entity->getReceiver());
        $incomingMessage->setAuthor($entity->getAuthor());
        $incomingMessage->setReceiver($entity->getReceiver());
        $incomingMessage->setConversation($entity->getConversation());
        $incomingMessage->setMessageBody($entity->getMessageBody());

        $args->getEntityManager()->persist($incomingMessage);
    }
}
