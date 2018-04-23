<?php

namespace Vidia\AuthBundle\Controller;

use Vidia\AuthBundle\Service\SessionService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class SessionController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("session", pluralize=false)
 */
class SessionController extends FOSRestController
{
    /**
     * Get sessions.
     *
     *
     * @SWG\Tag(name="Session")
     * @SWG\Response(
     *     response=200,
     *     description="Get session",
     *     @Model(type=Vidia\AuthBundle\Entity\Session::class)
     * )
     *
     * @Rest\View()
     *
     * @param Request $request
     *
     * @throws AuthenticationException
     */
    public function getAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('VidiaAuthBundle:Session');

        $sessions = $repository->findBy(['user' => $this->getUser()->getId()]);

        return $this->view($sessions);
    }

    /**
     * Kill current session.
     * @SWG\Tag(name="Session")
     * @SWG\Response(
     *     response=200,
     *     description="Kill current session"
     * )
     * @Rest\View()
     *
     * @param Request $request
     *
     * @throws AuthenticationException
     */
    public function postKillAction(Request $request)
    {
        /**
         * @var SessionService
         */
        $sessionService = $this->get('vidia_auth.session.service');

        $sessionService->killCurrent($this->getUser());

        return $this->view();
    }

    /**
     * Kill all session.
     *
     * @SWG\Tag(name="Session")
     * @SWG\Response(
     *     response=200,
     *     description="Kill all sessions",
     *     @Model(type=Vidia\AuthBundle\Entity\Session::class)
     * )
     * @Rest\View()
     *
     * @param Request $request
     *
     * @throws AuthenticationException
     */
    public function postKillAllAction(Request $request)
    {
        /**
         * @var SessionService
         */
        $sessionService = $this->get('vidia_auth.session.service');

        $sessionService->killAll($this->getUser());

        $repository = $this->getDoctrine()->getRepository('VidiaAuthBundle:Session');

        $sessions = $repository->findBy(['user' => $this->getUser()->getId()]);

        return $this->view($sessions);
    }
}
