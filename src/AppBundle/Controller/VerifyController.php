<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Service\VerifyService;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class VerifyController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Verify", pluralize=false)
 */
class VerifyController extends BaseRestController
{
    /**
     * Verify user.
     *
     * @param Request $request
     */
    public function postAction(Request $request)
    {
        $response = $this->responseErrorMessage('form_validation_error', [
            'code' => ['message' => $this->translate('not_blank', 'validators')],
        ]);

        $code = $request->get('code');

        /**
         * @var VerifyService
         */
        $verifyService = $this->get('app.verify.service');

        if (isset($code)) {
            $verifyStatus = $verifyService->verify($code);

            if ($verifyStatus instanceof User) {
                $response = $this->baseSerialize($verifyStatus);
            } else {
                $response = $this->baseSerialize($verifyStatus, [], 422);
            }
        }

        return $response;
    }
}
