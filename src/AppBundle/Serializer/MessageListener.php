<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Message;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use AppBundle\Entity\UserConversation;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class MessageListener implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.post_serialize', 'class' => 'AppBundle\Entity\Message', 'method' => 'onPostSerialize'),
        );
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        /**
         * @var Message $obj
         */
        $obj = $event->getObject();

        $userConversation = $this->getUserConversation($obj);

        if ($userConversation instanceof UserConversation) {
            /**
             * @var JsonSerializationVisitor
             */
            $visitor = $event->getVisitor();

            $visitor->addData('conversation', $userConversation);
        }


    }


    private function getUserConversation(Message $message)
    {
        $repository = $this->em->getRepository('AppBundle:UserConversation');

        $conversation = $repository->findOneBy(['user' => $message->getUser()->getId(), 'parentConversation' => $message->getConversation()->getId()]);

        return $conversation;
    }

}
