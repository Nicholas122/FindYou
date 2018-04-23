<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ExternalCharacteristic;
use AppBundle\Form\ExternalCharacteristicForm;
use Doctrine\ORM\EntityRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ExternalCharacteristicController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("External-Characteristic")
 */
class ExternalCharacteristicController extends BaseRestController
{
    /**
     * Add a new external characteristic.
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function postAction(Request $request)
    {
        $groups = [
            'serializerGroups' => [
                'default',
            ],
            'formOptions' => [
                'validation_groups' => [
                    'registration',
                    'default',
                    'Default',
                ],
            ],
        ];

        return $this->handleForm($request, ExternalCharacteristicForm::class, new ExternalCharacteristic(), $groups);
    }

    /**
     * Edit external characteristic.
     *
     *
     * @param Request                $request
     * @param ExternalCharacteristic $externalCharacteristic
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function putAction(Request $request, ExternalCharacteristic $externalCharacteristic)
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

        return $this->handleForm($request, ExternalCharacteristicForm::class, $externalCharacteristic, $groups, true);
    }

    /**
     * Return external characteristic by id.
     *
     * @Rest\View(serializerGroups={"default"})
     */
    public function getAction(ExternalCharacteristic $externalCharacteristic)
    {
        return $externalCharacteristic;
    }

    /**
     * Return externalCharacteristics.
     *
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="name", description="ExternalCharacteristic name")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:ExternalCharacteristic');
        $paramFetcher = $paramFetcher->all();

        return $this->matching($repository, $paramFetcher, null, ['default']);
    }

    /**
     * Delete external characteristic.
     *
     *
     * @Rest\View(statusCode=204)
     *
     * @param ExternalCharacteristic $externalCharacteristic
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(ExternalCharacteristic $externalCharacteristic)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($externalCharacteristic);
        $em->flush();

        return null;
    }
}
