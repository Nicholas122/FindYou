<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Form\PhotoForm;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
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
     *
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

        return $this->handleForm($request, new PhotoForm(), new Photo(), $groups);
    }

    /**
     * Delete photo.
     *
     *
     * @Rest\View(statusCode=204)
     *
     * @param Photo $photo
     *
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
