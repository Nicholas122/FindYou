<?php

namespace AppBundle\Controller;


use AppBundle\Entity\OutgoingMessage;
use AppBundle\Entity\Reply;
use AppBundle\Form\OutgoingMessageForm;
use AppBundle\Form\ReplyForm;
use AppBundle\Service\ReplyService;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class MessageController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("message")
 */
class MessageController extends BaseRestController
{

    /**
     * Send message.
     *
     *
     * @param Request $request
     *
     * @Security("has_role('ABILITY_MESSAGE_CREATE')")
     */
    public function postAction(Request $request)
    {
        $groups = [
            'serializerGroups' => [
                'default',
            ],
            'formOptions' => [
                'validation_groups' => [
                    'default',
                    'Default',
                ],
            ],
        ];

        return $this->handleForm($request, OutgoingMessageForm::class, new OutgoingMessage(), $groups);
    }

    /**
     * Return messages.
     *
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="conversation", description="Conversation id")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:Message');
        $paramFetcher = $paramFetcher->all();

        $paramFetcher['user'] = $this->getUser()->getId();

        return $this->matching($repository, $paramFetcher, null, ['default']);
    }
}