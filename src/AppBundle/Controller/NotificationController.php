<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class NotificationController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("notification")
 */
class NotificationController extends BaseRestController
{
    /**
     * Return notifications.
     * @Security("has_role('ROLE_USER')")
     * @SWG\Tag(name="Notification")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the notifications",
     *     @Model(type=AppBundle\Entity\Notification::class)
     * )
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="isRead", description="Is read")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:Notification');
        $paramFetcher = $paramFetcher->all();

        $paramFetcher['receiver'] = $this->getUser()->getId();

        return $this->matching($repository, $paramFetcher, null, ['default']);
    }
}