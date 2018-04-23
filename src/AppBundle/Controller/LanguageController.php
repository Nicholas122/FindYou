<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Language;
use AppBundle\Form\LanguageForm;
use Doctrine\ORM\EntityRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class LanguageController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Language")
 */
class LanguageController extends BaseRestController
{
    /**
     * Add a new language.
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

        return $this->handleForm($request, LanguageForm::class, new Language(), $groups);
    }

    /**
     * Edit language.
     *
     *
     * @param Request  $request
     * @param Language $language
     *
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function putAction(Request $request, Language $language)
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

        return $this->handleForm($request, LanguageForm::class, $language, $groups, true);
    }

    /**
     * Return language by id.
     *
     * @Rest\View(serializerGroups={"default"})
     */
    public function getAction(Language $language)
    {
        return $language;
    }

    /**
     * Return languages.
     *
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="locale", description="Language locale")
     * @Rest\QueryParam(name="name", description="Language name")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:Language');
        $paramFetcher = $paramFetcher->all();

        return $this->matching($repository, $paramFetcher, null, ['default']);
    }

    /**
     * Delete language.
     *
     *
     * @Rest\View(statusCode=204)
     *
     * @param Language $language
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Language $language)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($language);
        $em->flush();

        return null;
    }
}
