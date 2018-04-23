<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class PostListener implements EventSubscriberInterface
{
    protected $tokenStorage;

    protected $requestStack;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.post_serialize', 'class' => 'AppBundle\Entity\Post', 'method' => 'onPostSerialize'),
        );
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        /**
         * @var Post
         */
        $obj = $event->getObject();

        $isMine = intval($this->isMine($obj));
        $locale = $this->getLocale();
        $place = null;

        /**
         * @var JsonSerializationVisitor
         */
        $visitor = $event->getVisitor();

        if ($obj->getIsAnonymously()) {
            $visitor->setData('user', null);
        }

        if ($obj->getPlace() && array_key_exists($locale, $obj->getPlace())) {
            $place = $obj->getPlace()[$locale];
        }

        $visitor->setData('isMine', $isMine);
        $visitor->setData('place', $place);
    }

    private function isMine(Post $post)
    {
        /**
         * @var User
         */
        $currentUser = $this->tokenStorage->getToken()->getUser();

        return $post->getUser()->getId() === $currentUser->getId();
    }

    private function getLocale()
    {
        /**
         * @var User
         */
        $currentUser = $this->tokenStorage->getToken()->getUser();

        $language = $currentUser->getLanguage();

        $locale = $language->getLocale();

        return $locale;
    }
}
