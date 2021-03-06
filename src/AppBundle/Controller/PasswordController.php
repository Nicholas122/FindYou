<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserForm;
use AppBundle\Service\PasswordService;
use AppBundle\Service\SmsService;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class PasswordController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Password",pluralize=false)
 */
class PasswordController extends BaseRestController
{
    /**
     * Change password.
     *
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Security("is_granted('ABILITY_USER_UPDATE', user)")
     */
    public function putChangeAction(Request $request)
    {
        $user = $this->getUser();
        $error = [];
        /**
         * @var PasswordService
         */
        $passwordService = $this->get('app.password.service');

        $oldPassword = $request->request->get('oldPassword');
        $newPassword = $request->request->get('newPassword');

        if (empty($oldPassword)) {
            $error['oldPassword'] = ['message' => $this->translate('not_blank', 'validators')];
        } elseif (!$passwordService->isPasswordValid($user, $oldPassword)) {
            $error['oldPassword'] = ['message' => $this->translate('old_password_is_not_correct', 'validators')];
        }

        if (empty($newPassword)) {
            $error['newPassword'] = ['message' => $this->translate('not_blank', 'validators')];
        }

        $response = $this->responseErrorMessage('change_password_error', $error);

        if (empty($error)) {
            $request->request->set('password', $newPassword);

            $groups = [
                'serializerGroups' => [
                    'default',
                    'user'
                ],
                'formOptions' => [
                    'validation_groups' => [
                        'default',
                        'Default',
                    ],
                ],
            ];

            $response = $this->handleForm($request, UserForm::class, $user, $groups, true);
        }

        return $response;
    }

    /**
     * Generate access key.
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Rest\Post("password/access-key")
     */
    public function postAccessKeyAction(Request $request)
    {
        $error = [];

        /**
         * @var PasswordService
         */
        $passwordService = $this->get('app.password.service');

        $phone = $request->request->get('phone');

        if (empty($phone)) {
            $error['phone'] = ['message' => $this->translate('not_blank', 'validators')];
        }

        $response = $this->responseErrorMessage('restore_password_error', $error);

        if (empty($error)) {
            $accessKey = $passwordService->generateAccessKey($phone);

            if ($accessKey) {
                /**
                 * @var SmsService $smsService
                 */
                $smsService = $this->get('app.sms.service');
                
                $smsService->send($accessKey, $phone);
               
                $response = $this->responseMessage('OK', ['data' => ['message' => 'Access key sent successfully']]);
            } else {
                $error['sms'] = ['message' => $this->translate('can_not_proccess_sms', 'validators')];
                $response = $this->responseErrorMessage('restore_password_error', $error);
            }
        }

        return $response;
    }

    /**
     * Generate access key.
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function postRestoreAction(Request $request)
    {
        $error = [];

        /**
         * @var PasswordService
         */
        $passwordService = $this->get('app.password.service');

        $accessKey = $request->request->get('accessKey');
        $newPassword = $request->request->get('newPassword');

        if (empty($accessKey)) {
            $error['accessKey'] = ['message' => $this->translate('not_blank', 'validators')];
        }

        if (empty($newPassword)) {
            $error['newPassword'] = ['message' => $this->translate('not_blank', 'validators')];
        }

        $response = $this->responseErrorMessage('restore_password_error', $error);

        if (empty($error)) {
            $isPasswordRestored = $passwordService->restorePassword($newPassword, $accessKey);

            if ($isPasswordRestored) {
                $response = $this->responseMessage('OK', ['data' => ['message' => 'Password changed successfully']]);
            } else {
                $error['accessKey'] = ['message' => $this->translate('is_not_valid_restore_access_key', 'validators')];
                $response = $this->responseErrorMessage('restore_password_error', $error);
            }
        }

        return $response;
    }
}
