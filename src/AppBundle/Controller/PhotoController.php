<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\PhotoForm;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class PhotoController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("Photo", pluralize=false)
 */
class PhotoController extends BaseRestController
{
    /**
     * Add a new photo.
     *
     *
     * @param Request $request
     * @SWG\Post(
     *     tags={"Photo"},
     *     consumes={"application/form-data"},
     *     @SWG\Response(
     *       response=200,
     *       description="Upload photo",
     *       @Model(type=AppBundle\Entity\Photo::class)
     *     ),
     *     @SWG\Parameter(
     *         name="form",
     *         in="body",
     *         description="Request params",
     *         @Model(type=AppBundle\Form\PhotoForm::class)
     *     )
     *
     * )
     * @Security("has_role('ABILITY_PHOTO_CREATE')")
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

        return $this->handleForm($request, PhotoForm::class, new Photo(), $groups);
    }

    /**
     * Delete photo.
     *
     *
     * @Rest\View(statusCode=204)
     *
     * @param Photo $photo
     * @SWG\Tag(name="Photo")
     * @SWG\Response(
     *     response=204,
     *     description="Delete photo",
     * )
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ABILITY_PHOTO_DELETE', photo)")
     */
    public function deleteAction(Photo $photo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();

        return null;
    }
}
