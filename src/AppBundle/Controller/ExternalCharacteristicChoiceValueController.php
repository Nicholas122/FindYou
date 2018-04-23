<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ExternalCharacteristicChoiceValue;
use AppBundle\Form\ExternalCharacteristicChoiceValueForm;
use Doctrine\ORM\EntityRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ExternalCharacteristicChoiceValueController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("External-Characteristic-Value")
 */
class ExternalCharacteristicChoiceValueController extends BaseRestController
{
    /**
     * Add a new external characteristic choice value.
     *
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

        return $this->handleForm($request, ExternalCharacteristicChoiceValueForm::class, new ExternalCharacteristicChoiceValue(), $groups);
    }

    /**
     * Edit external characteristic choice value.
     *
     *
     * @param Request                           $request
     * @param ExternalCharacteristicChoiceValue $externalCharacteristicValue
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function putAction(Request $request, ExternalCharacteristicChoiceValue $externalCharacteristicValue)
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

        return $this->handleForm($request, ExternalCharacteristicChoiceValueForm::class, $externalCharacteristicValue, $groups, true);
    }

    /**
     * Return external characteristic choice value by id.
     *
     * @Rest\View(serializerGroups={"default"})
     */
    public function getAction(ExternalCharacteristicChoiceValue $externalCharacteristicValue)
    {
        return $externalCharacteristicValue;
    }

    /**
     * Return external characteristic choice values.
     *
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="name", description="ExternalCharacteristicChoiceValue name")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:ExternalCharacteristicChoiceValue');
        $paramFetcher = $paramFetcher->all();

        return $this->matching($repository, $paramFetcher, null, ['default']);
    }

    /**
     * Delete external characteristic choice value.
     *
     *
     * @Rest\View(statusCode=204)
     *
     * @param ExternalCharacteristicChoiceValue $externalCharacteristicValue
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(ExternalCharacteristicChoiceValue $externalCharacteristicValue)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($externalCharacteristicValue);
        $em->flush();

        return null;
    }
}
