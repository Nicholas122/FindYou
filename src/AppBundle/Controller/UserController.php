<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserForm;
use AppBundle\Service\VerifyService;
use Doctrine\ORM\EntityRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class UserController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("User")
 */
class UserController extends BaseRestController
{
    /**
     * Create a new user.
     *
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function postAction(Request $request)
    {
        $groups = [
            'serializerGroups' => [
                'default',
                'auth',
            ],
            'formOptions' => [
                'validation_groups' => [
                    'registration',
                    'default',
                    'Default',
                ],
            ],
        ];

        $user = new User();

        $response = $this->handleForm($request, UserForm::class, $user, $groups);

        if (201 === $response->getStatusCode() && !in_array($this->getParameter('kernel.environment'), ['test'])) {
            /**
             * @var VerifyService $verifyService
             */
            $verifyService = $this->get('app.verify.service');

            $verifyService->sendVerifyCode($user);

            $response = $this->responseMessage('OK', ['data' => ['message' => 'Verify code sent successfully']]);
        }


        return $response;
    }

    /**
     * Edit user data.
     *
     *
     * @param Request $request
     * @param User    $user
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Security("is_granted('ABILITY_USER_UPDATE', user)")
     */
    public function putAction(Request $request, User $user)
    {
        $groups = [
            'serializerGroups' => [
                'default',
                'user',
            ],
            'formOptions' => [
                'validation_groups' => [
                    'default',
                    'Default',
                ],
            ],
        ];

        return $this->handleForm($request, UserForm::class, $user, $groups, true);
    }

    /**
     * Return user by id.
     *
     * @Rest\View(serializerGroups={"default", "user"})
     */
    public function getAction(User $user)
    {
        return $user;
    }

    /**
     * Return users.
     *
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="username", description="Username")
     * @Rest\QueryParam(name="fullName", description="Full name")
     * @Rest\QueryParam(name="email", description="E-mail")
     * @Rest\QueryParam(name="dateOfBirth", description="Date of birth")
     * @Rest\QueryParam(name="currentLocation", description="Current location")
     * @Rest\QueryParam(name="facebookId", description="Facebook ID")
     * @Rest\QueryParam(name="googleId", description="Google ID")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:User');
        $paramFetcher = $paramFetcher->all();

        return $this->matching($repository, $paramFetcher, null, ['default', 'list']);
    }

    /**
     * Get current user.
     *
     *
     * @param Request $request
     *
     * @Rest\View(serializerGroups={"default"})
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function getMeAction()
    {
        return $this->getUser();
    }

    /**
     * Delete user.
     *
     *
     * @Rest\View(statusCode=204)
     *
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ABILITY_USER_DELETE', user)")
     */
    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return null;
    }
}
