<?php


namespace AppBundle\Service;


use AppBundle\Entity\Conversation;
use AppBundle\Entity\IncomingMessage;
use AppBundle\Entity\OutgoingMessage;
use AppBundle\Entity\Post;
use AppBundle\Entity\Reply;
use AppBundle\Entity\User;
use AppBundle\Entity\UserConversation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ReplyService
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function process(Reply $reply, User $user)
    {
        $conversation = $this->createConversation($reply->getPost());

        $authorConversation = $this->createUserConversations($conversation, $user, $reply->getPost()->getUser());

        $this->createMessages($conversation, $reply, $user);

        return $authorConversation;
    }

    private function createConversation(Post $post)
    {
        $conversation = new Conversation();

        $conversation->setPost($post);

        $this->em->persist($conversation);

        $this->em->flush();

        return $conversation;
    }

    private function createUserConversations(Conversation $conversation, User $author, User $receiver)
    {
        $guid = guid();

        $authorConversation = new UserConversation();
        $authorConversation->setUser($author);
        $authorConversation->setParentConversation($conversation);
        $authorConversation->setPost($conversation->getPost());
        $authorConversation->setReceiver($receiver);
        $authorConversation->setGuid($guid);

        $receiverConversation = new UserConversation();
        $receiverConversation->setUser($receiver);
        $receiverConversation->setParentConversation($conversation);
        $receiverConversation->setPost($conversation->getPost());
        $receiverConversation->setReceiver($author);
        $receiverConversation->setGuid($guid);

        $this->em->persist($authorConversation);
        $this->em->persist($receiverConversation);

        $this->em->flush();

        return $authorConversation;
    }

    private function createMessages(Conversation $conversation, Reply $reply, User $author)
    {
        $outgoingMessage = new OutgoingMessage();
        $outgoingMessage->setAuthor($author);
        $outgoingMessage->setReceiver($reply->getPost()->getUser());
        $outgoingMessage->setConversation($conversation);
        $outgoingMessage->setMessageBody($reply->getMessageBody());
        $outgoingMessage->setUser($author);

        $this->em->persist($outgoingMessage);

        $this->em->flush();

    }

    private function guid()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}