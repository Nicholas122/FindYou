<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Reply;
use AppBundle\Form\ReplyForm;
use AppBundle\Service\ReplyService;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ConversationController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("conversation")
 */
class ConversationController extends BaseRestController
{
    /**
     * Return conversations.
     *
     * @SWG\Tag(name="Conversation")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the conversations",
     *     @Model(type=AppBundle\Entity\UserConversation::class)
     * )
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     *
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:UserConversation');
        $paramFetcher = $paramFetcher->all();

        $paramFetcher['user'] = $this->getUser()->getId();

        return $this->matching($repository, $paramFetcher, null, ['default']);
    }
}