<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\User;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class UserListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.post_serialize', 'class' => 'AppBundle\Entity\User', 'method' => 'onPostSerialize'),
        );
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        /**
         * @var User
         */
        $obj = $event->getObject();

        $visitor = $event->getVisitor();
        $hasPhoto = !empty($obj->getPhoto());
        if (!$hasPhoto) {
            $visitor->setData('photo', ['path' => 'files/user/default/default_profile.png']);
        }

        $visitor->addData('hasPhoto', $hasPhoto);
    }
}
