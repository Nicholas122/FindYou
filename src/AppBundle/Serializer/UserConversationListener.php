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
            array('event' => 'serializer.pre_serialize', 'class' => 'AppBundle\Entity\UserConversation', 'method' => 'onPreSerialize'),
        );
    }

    public function onPreSerialize(ObjectEvent $event)
    {
        /**
         * @var User
         */
        $obj = $event->getObject();

        $visitor = $event->getVisitor();
        $visitor->setData('lastActivity', $this->getLastActivity($obj));
    }

    private function getLastActivity(UserConversation $userConversation)
    {
        $response = '';

        $repository = $this->em->getRepository('AppBundle:Message');

        $message = $repository->findBy([
            'conversation' => $userConversation->getParentConversation()->getId()
        ], ['creationDate' => 'DESC']);

        if ($message instanceof Message) {
            $response = $message->getCreationDate()->format('y-m-d H:i:s');
        }

        return $response;
    }
}
