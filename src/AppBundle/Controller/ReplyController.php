<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Reply;
use AppBundle\Form\ReplyForm;
use AppBundle\Service\ReplyService;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ReplyController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("reply", pluralize=false)
 */
class ReplyController extends BaseRestController
{
    /**
     * Create new reply.
     *
     *
     * @param Request $request
     *
     * @Security("has_role('ABILITY_REPLY_CREATE')")
     */
    public function postAction(Request $request)
    {
        $reply = new Reply();

        $response = $this->handleForm($request, ReplyForm::class, $reply, ['persist' => false]);

        if ($response->getStatusCode() === 201) {
            $this->getReplyService()->process($reply, $this->getUser());
        }

        return $response;
    }

    private function getReplyService()
    {
        /**
         * @var ReplyService $replyService
         */
        $replyService = $this->get('app.reply.service');

        return $replyService;
    }
}