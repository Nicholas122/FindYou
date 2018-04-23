<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TranslationConstantValue;
use AppBundle\Form\TranslationConstantValueForm;
use Doctrine\ORM\EntityRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class TranslationConstantValueController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Translation-Constant-Value")
 */
class TranslationConstantValueController extends BaseRestController
{
    /**
     * Create a new translation constant value.
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

        return $this->handleForm($request, new TranslationConstantValueForm(), new TranslationConstantValue(), $groups);
    }

    /**
     * Edit translation constant value.
     *
     *
     * @param Request                  $request
     * @param TranslationConstantValue $translationConstant
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function putAction(Request $request, TranslationConstantValue $translationConstantValue)
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

        return $this->handleForm($request, new TranslationConstantValueForm(), $translationConstantValue, $groups, true);
    }

    /**
     * Return translation constant value by id.
     *
     * @Rest\View(serializerGroups={"default"})
     */
    public function getAction(TranslationConstantValue $translationConstantValue)
    {
        return $translationConstantValue;
    }

    /**
     * Return translation constant values.
     *
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="value", description="Value")
     * @Rest\QueryParam(name="language", description="Language")
     * @Rest\QueryParam(name="translationConstant", description="TranslationConstant")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:TranslationConstantValue');
        $paramFetcher = $paramFetcher->all();

        return $this->matching($repository, $paramFetcher, null, ['default']);
    }

    /**
     * Delete translation constant value.
     *
     *
     * @Rest\View(statusCode=204)
     *
     * @param TranslationConstantValue $translationConstantValue
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(TranslationConstantValue $translationConstantValue)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($translationConstantValue);
        $em->flush();

        return null;
    }
}
