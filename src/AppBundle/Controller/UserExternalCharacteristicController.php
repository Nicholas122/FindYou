<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserExternalCharacteristic;
use AppBundle\Form\UserExternalCharacteristicForm;
use Doctrine\ORM\EntityRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class UserExternalCharacteristicController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("User-External-Characteristic")
 */
class UserExternalCharacteristicController extends BaseRestController
{
    /**
     * Add a new user external characteristic.
     *
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Security("has_role('ABILITY_USER_EXTERNAL_CHARACTERISTIC_CREATE')")
     */
    public function postAction(Request $request)
    {
        $groups = [
            'serializerGroups' => [
                'default',
                'detail',
            ],
            'formOptions' => [
                'validation_groups' => [
                    'registration',
                    'default',
                    'Default',
                ],
            ],
        ];

        $request->request->set('user', $this->getUser()->getId());

        return $this->handleForm($request, UserExternalCharacteristicForm::class, new UserExternalCharacteristic(), $groups);
    }

    /**
     * Edit user external characteristic.
     *
     *
     * @param Request                    $request
     * @param UserExternalCharacteristic $externalCharacteristic
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Security("is_granted('ABILITY_USER_EXTERNAL_CHARACTERISTIC_UPDATE', externalCharacteristic)")
     */
    public function putAction(Request $request, UserExternalCharacteristic $externalCharacteristic)
    {
        $groups = [
            'serializerGroups' => [
                'default',
                'detail',
            ],
            'formOptions' => [
                'validation_groups' => [
                    'default',
                    'Default',
                ],
            ],
        ];

        return $this->handleForm($request, UserExternalCharacteristicForm::class, $externalCharacteristic, $groups, true);
    }

    /**
     * Return user external characteristic by id.
     *
     * @Rest\View(serializerGroups={"detail"})
     */
    public function getAction(UserExternalCharacteristic $externalCharacteristic)
    {
        return $externalCharacteristic;
    }

    /**
     * Return externalCharacteristics.
     *
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="user", description="name")
     * @Rest\QueryParam(name="value", description="value")
     * @Rest\QueryParam(name="externalCharacteristic", description="external characteristic")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:UserExternalCharacteristic');
        $paramFetcher = $paramFetcher->all();

        return $this->matching($repository, $paramFetcher, null, ['default']);
    }

    /**
     * Delete user external characteristic.
     *
     *
     * @Rest\View(statusCode=204)
     *
     * @param UserExternalCharacteristic $externalCharacteristic
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ABILITY_USER_EXTERNAL_CHARACTERISTIC_DELETE', externalCharacteristic)")
     */
    public function deleteAction(UserExternalCharacteristic $externalCharacteristic)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($externalCharacteristic);
        $em->flush();

        return null;
    }
}
