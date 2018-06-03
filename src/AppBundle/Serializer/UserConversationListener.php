<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Entity\UserConversation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class UserConversationListener implements EventSubscriberInterface
{
    protected  $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.post_serialize', 'class' => 'AppBundle\Entity\UserConversation', 'method' => 'onPostSerialize'),
        );
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        /**
         * @var User
         */
        $obj = $event->getObject();

        $visitor = $event->getVisitor();
        $visitor->addData('lastActivity', $this->getLastActivity($obj));
    }

    private function getLastActivity(UserConversation $userConversation)
    {
        $response = null;

        $repository = $this->em->getRepository('AppBundle:Message');

        $messages = $repository->findBy([
            'conversation' => $userConversation->getParentConversation()->getId()
        ], ['creationDate' => 'DESC']);

        if (count($messages) > 0 && $messages[0] instanceof Message) {
            /**
             * @var Message $message
             */
            $message = $messages[0];

            $response = $message->getCreationDate()->format(DATE_W3C);
        }

        return $response;
    }
}
