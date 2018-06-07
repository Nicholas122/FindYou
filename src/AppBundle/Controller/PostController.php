<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Exception\PostLimitException;
use AppBundle\Form\PostForm;
use AppBundle\Service\Google\GoogleGeocodeService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class PostController.
 *
 * @Rest\NamePrefix("api_")
 * @Rest\RouteResource("post")
 */
class PostController extends BaseRestController
{
    /**
     * Create new post.
     *
     *
     * @param Request $request
     *
     * @Security("has_role('ABILITY_POST_CREATE')")
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

        try {
            $response = $this->handleForm($request, PostForm::class, new Post(), $groups);
        } catch (PostLimitException $exception) {
            $response = $response = $this->responseErrorMessage('post_creation_error', [
                'code' => ['message' => $this->translate('post_limit_error', 'validators')],
            ], 423);
        }

        return $response;
    }

    /**
     * Edit post.
     *
     *
     * @param Request $request
     * @param Post $post
     *
     * @Security("is_granted('ABILITY_POST_UPDATE', post)")
     */
    public function putAction(Request $request, Post $post)
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

        return $this->handleForm($request, PostForm::class, $post, $groups, true);
    }

    /**
     * Return post by id.
     *
     * @Rest\View()
     */
    public function getAction(Post $post)
    {
        return $post;
    }

    /**
     * Return posts.
     *
     * @Rest\QueryParam(name="_sort")
     * @Rest\QueryParam(name="_limit",  requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="_offset", requirements="\d+", nullable=true, strict=true)
     * @Rest\QueryParam(name="user", description="User")
     * @Rest\QueryParam(name="placeId", description="Place id")
     * @Rest\QueryParam(name="day", description="Day")
     * @Rest\QueryParam(name="startOfRange", description="Start of range")
     * @Rest\QueryParam(name="endOfRange", description="End of range")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getRepository('AppBundle:Post');
        $paramFetcher = $paramFetcher->all();

        $filterCallback = function (QueryBuilder $criteria) use ($paramFetcher) {
            if ($paramFetcher['placeId']) {
                /**
                 * @var GoogleGeocodeService $googleGeocodeService
                 */
                $googleGeocodeService = $this->get('app.google_geocode.service');

                $coords = $googleGeocodeService->getCoordsByPlaceId($paramFetcher['placeId']);

                $criteria
                    ->select('entity',
                        '( 6371 * acos( cos( radians(' . $coords['lat'] . ') ) * ' .
                        'cos( radians( entity.lat ) ) * ' .
                        'cos( radians( entity.lng ) - radians(' . $coords['lng'] . ') ) + ' .
                        'sin( radians(' . $coords['lat'] . ') ) * sin( radians( entity.lat ) ) ) ) AS HIDDEN distance')
                    ->having('distance <= 60')
                    ->orderBy('distance', 'ASC');


            }

            if ($paramFetcher['day'] && $paramFetcher['startOfRange'] && $paramFetcher['endOfRange']) {
                $day = new \DateTime($paramFetcher['day']);
                $endOfRange = [
                    'h' => date('H', strtotime($paramFetcher['endOfRange'])),
                    'i' => date('i', strtotime($paramFetcher['endOfRange'])),
                    's' => date('s', strtotime($paramFetcher['endOfRange']))
                ];

                $startOfRange = [
                    'h' => date('H', strtotime($paramFetcher['startOfRange'])),
                    'i' => date('i', strtotime($paramFetcher['startOfRange'])),
                    's' => date('s', strtotime($paramFetcher['startOfRange']))
                ];

                $meetingDateStart = new \DateTime();
                $meetingDateStart->setDate(
                    intval($day->format('Y')),
                    intval($day->format('m')),
                    intval($day->format('d'))
                );

                $meetingDateStart->setTime($startOfRange['h'], $startOfRange['i'], $startOfRange['s']);

                $meetingDateEnd = new \DateTime();
                $meetingDateEnd->setDate(
                    intval($day->format('Y')),
                    intval($day->format('m')),
                    intval($day->format('d'))
                );

                $meetingDateEnd->setTime($endOfRange['h'], $endOfRange['i'], $endOfRange['s']);

                $criteria->andWhere(
                    $criteria->expr()->gte(
                        'entity.meetingDateStart',
                        $criteria->expr()->literal($meetingDateStart->format('Y-m-d H:i:s'))));
                $criteria->andWhere(
                    $criteria->expr()->lte(
                        'entity.meetingDateEnd',
                        $criteria->expr()->literal($meetingDateEnd->format('Y-m-d H:i:s'))));
            }
        };
        unset($paramFetcher['placeId']);

        $paramFetcher['isDeleted'] = 0;

        return $this->matching($repository, $paramFetcher, $filterCallback, ['default']);
    }

    /**
     * Delete post.
     *
     * @Rest\View(statusCode=204)
     *
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ABILITY_POST_DELETE', post)")
     */
    public function deleteAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return null;
    }
}
