<?php

namespace AppBundle\EventListener\DoctrineEntityListener;

use AppBundle\Entity\IncomingMessage;
use AppBundle\Entity\Notification;
use AppBundle\Entity\OutgoingMessage;
use AppBundle\Entity\Photo;
use AppBundle\Entity\UserConversation;
use AppBundle\Service\FcmService;
use AppBundle\Service\Google\GoogleGeocodeService;
use AppBundle\Service\PhotoService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class OutgoingMessageEntityListener
{
    private  $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

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
     * Create Notification.
     *
     * @ORM\PrePersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function createNotification(OutgoingMessage $entity, LifecycleEventArgs $args)
    {
        $notification = new Notification();
        $notification->setReceiver($entity->getReceiver());
        $notification->setTitle('Message');
        $notification->setType('info');
        $notification->setLink('https://www.findyou.com.ua/replies');
        $notification->setBody($entity->getMessageBody());
        $notification->setIsRead(0);
        $notification->setAuthor($entity->getAuthor());

        $this->fcmService->sendNotification($notification);

        $args->getEntityManager()->persist($notification);
    }

    /**
     * Create IncomingMessage.
     *
     * @ORM\PostPersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function createIncomingMessage(OutgoingMessage $entity, LifecycleEventArgs $args)
    {
        $incomingMessage = new IncomingMessage();
        $incomingMessage->setUser($entity->getReceiver());
        $incomingMessage->setAuthor($entity->getUser());
        $incomingMessage->setReceiver($entity->getReceiver());
        $incomingMessage->setConversation($entity->getConversation());
        $incomingMessage->setMessageBody($entity->getMessageBody());

        $args->getEntityManager()->persist($incomingMessage);
        $args->getEntityManager()->flush();
    }

}
