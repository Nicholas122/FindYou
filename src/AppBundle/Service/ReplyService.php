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

        $this->createUserConversations($conversation, $user, $reply->getPost()->getUser());

        $this->createMessages($conversation, $reply, $user);
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
        $authorConversation = new UserConversation();
        $authorConversation->setUser($author);
        $authorConversation->setParentConversation($conversation);
        $authorConversation->setPost($conversation->getPost());
        $authorConversation->setReceiver($receiver);

        $receiverConversation = new UserConversation();
        $receiverConversation->setUser($receiver);
        $receiverConversation->setParentConversation($conversation);
        $receiverConversation->setPost($conversation->getPost());
        $receiverConversation->setReceiver($author);

        $this->em->persist($authorConversation);
        $this->em->persist($receiverConversation);

        $this->em->flush();
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
}