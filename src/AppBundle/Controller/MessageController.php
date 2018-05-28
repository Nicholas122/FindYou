<?php

namespace AppBundle\Controller;


use AppBundle\Entity\OutgoingMessage;
use AppBundle\Entity\Reply;
use AppBundle\Entity\UserConversation;
use AppBundle\Form\OutgoingMessageForm;
use AppBundle\Form\ReplyForm;
use AppBundle\Service\ReplyService;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\Model;

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
     * @SWG\Tag(name="Message")
     * @SWG\Response(
     *     response=200,
     *     description="Create message",
     *     @Model(type=AppBundle\Entity\OutgoingMessage::class)
     * )
     * @SWG\Parameter(
     *         name="form",
     *         in="body",
     *         description="Request params",
     *         @Model(type=AppBundle\Form\OutgoingMessageForm::class)
     *     )
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
     * @SWG\Tag(name="Message")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the user message",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=AppBundle\Entity\OutgoingMessage::class))
     *     )
     * )
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:Message');
        $paramFetcher = $paramFetcher->all();
        $paramFetcher['user'] = $this->getUser()->getId();
        $paramFetcher['conversation'] = $this->getConversationIdByChildId($paramFetcher['conversation']);

        return $this->matching($repository, $paramFetcher, null, ['default']);
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
        }
        else {
            $response = null;
        }

        return $response;
    }


}