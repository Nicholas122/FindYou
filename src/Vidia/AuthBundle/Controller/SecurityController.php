<?php

namespace Vidia\AuthBundle\Controller;

use Symfony\Component\Translation\Translator;
use Vidia\AuthBundle\Entity\UserInterface;
use Vidia\AuthBundle\Service\UserAuthService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class SecurityController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Authorize", pluralize=false)
 */
class SecurityController extends FOSRestController
{
    /**
     * User authentication.
     * @SWG\Tag(name="Security")
     * @SWG\Response(
     *     response=200,
     *     description="Authorization",
     *     @Model(type=AppBundle\Entity\User::class)
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="User email"
     * )
     *
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string",
     *     description="User password"
     * )
     *
     * @Rest\View(serializerGroups={"auth"})
     *
     * @param Request $request
     *
     * @throws AuthenticationException
     */
    public function postAction(Request $request)
    {
        $statusCode = 200;

        $authService = $this->getAuthServcie();
        /** @var UserInterface $user */
        $response = $authService->authorize($request);
        if (false === $response) {
            $response = $this->responseErrorMessage('auth_bad_credentials', ['credentials' => [
                'message' => $this->translate('bad_credentials', 'validators'),
            ]]);

            $statusCode = 401;
        }

        return $this->view($response, $statusCode);
    }

    /**
     * Refresh token.
     *
     * @SWG\Tag(name="Security")
     * @SWG\Response(
     *     response=200,
     *     description="Refresh token",
     *     @Model(type=AppBundle\Entity\User::class)
     * )
     * @SWG\Parameter(
     *     name="accessToken",
     *     in="query",
     *     type="string",
     *     description="Current access token"
     * )
     *
     * @SWG\Parameter(
     *     name="refreshToken",
     *     in="query",
     *     type="string",
     *     description="Refresh token"
     * )
     * @Rest\View(serializerGroups={"auth"})
     *
     * @param Request $request
     *
     * @throws AuthenticationException
     * @Rest\Post("/refresh-token")
     */
    public function postRefreshTokenAction(Request $request)
    {
        $statusCode = 200;

        $authService = $this->getAuthServcie();
        /** @var UserInterface $user */
        $response = $authService->authorize($request);
        if (false === $response) {
            $response = $this->responseErrorMessage('invalid_refresh_token', ['credentials' => [
                'message' => $this->translate('invalid_refresh_token', 'validators'),
            ]]);

            $statusCode = 401;
        }

        return $this->view($response, $statusCode);
    }

    /**
     * @return UserAuthService
     */
    private function getAuthServcie()
    {
        /**
         * @var UserAuthService
         */
        $userAuthService = $this->get('vidia_auth.user_auth.service');

        return $userAuthService;
    }

    private function responseErrorMessage($errorType, array $errors, $statusCode = 422)
    {
        $response = [
            'error_type' => $errorType,
            'errors' => $errors,
        ];

        return $response;
    }

    private function translate($code, $domain)
    {
        /**
         * @var Translator
         */
        $translator = $this->get('translator');

        $translate = $translator->trans($code, [], $domain);

        return $translate;
    }
}
