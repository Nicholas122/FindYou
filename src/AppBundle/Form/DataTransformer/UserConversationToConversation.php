<?php


namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\UserConversation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserConversationToConversation implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($value)
    {
        return $this->getConversationIdByChildId($value);
    }


    public function reverseTransform($value)
    {
        return $value;
    }

    public function getConversationIdByChildId($id)
    {
        $repository = $this->getRepository('AppBundle:UserConversation');

        /**
         * @var UserConversation $userConvrsation
         */
        $userConvrsation = $repository->findOneById($id);

        if ($userConvrsation instanceof UserConversation) {
            $response = $userConvrsation->getParentConversation()->getId();
        } else {
            $response = $id;
        }

        return $response;
    }
}