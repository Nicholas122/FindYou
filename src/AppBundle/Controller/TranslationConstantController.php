<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TranslationConstant;
use AppBundle\Form\TranslationConstantForm;
use AppBundle\Service\TranslationConstantService;
use Doctrine\ORM\EntityRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class TranslationConstantController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Translation-Constant")
 */
class TranslationConstantController extends BaseRestController
{
    /**
     * Create a new translation constant.
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

        return $this->handleForm($request, TranslationConstantForm::class, new TranslationConstant(), $groups);
    }

    /**
     * Edit translation constant.
     *
     * @param Request             $request
     * @param TranslationConstant $translationConstant
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function putAction(Request $request, TranslationConstant $translationConstant)
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

        return $this->handleForm($request, TranslationConstantForm::class, $translationConstant, $groups, true);
    }

    /**
     * Return translation constant by id.
     *
     * @Rest\View(serializerGroups={"default"})
     */
    public function getAction(TranslationConstant $translationConstant)
    {
        return $translationConstant;
    }

    /**
     * Return translation constants.
     *
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="name", description="TranslationConstant name")
     */
    public function cgetAction(ParamFetcher $paramFetcher, Request $request)
    {
        /**
         * @var TranslationConstantService
         */
        $translationConstantService = $this->get('app.translation_constant.service');

        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:TranslationConstant');
        $paramFetcher = $paramFetcher->all();

        $translationsConstants = $this->matching($repository, $paramFetcher, null, ['default'], ['returnEntities' => true]);

        $translationsConstants = $translationConstantService->transform($translationsConstants, $request);

        return $this->baseSerialize($translationsConstants);
    }

    /**
     * Delete translation constant.
     *
     *
     * @Rest\View(statusCode=204)
     *
     * @param TranslationConstant $translationConstant
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(TranslationConstant $translationConstant)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($translationConstant);
        $em->flush();

        return null;
    }
}
