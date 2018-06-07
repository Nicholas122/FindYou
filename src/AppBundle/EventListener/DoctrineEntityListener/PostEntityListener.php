<?php

namespace AppBundle\EventListener\DoctrineEntityListener;

use AppBundle\Entity\Photo;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use AppBundle\Exception\PostLimitException;
use AppBundle\Repository\PostRepository;
use AppBundle\Service\Google\GoogleGeocodeService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class PostEntityListener
{
    protected $requestStack;

    protected $googleGeocodeService;

    protected $tokenStorage;

    public function __construct(RequestStack $requestStack, GoogleGeocodeService $googleGeocodeService, TokenStorage $tokenStorage)
    {
        $this->requestStack = $requestStack;

        $this->googleGeocodeService = $googleGeocodeService;

        $this->tokenStorage = $tokenStorage;
    }

    /**
     * CheckPostLimit.
     *
     * @ORM\PrePersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function checkPostLimit(Post $entity, LifecycleEventArgs $args)
    {
        $postLimit = 15;

        $em = $args->getEntityManager();

        $user = $this->tokenStorage->getToken()->getUser();

        if ($user instanceof User) {
            /**
             * @var PostRepository $repository
             */
            $repository = $em->getRepository('AppBundle:Post');

            $createdTodayProductCount = $repository->getCreatedTodayProductCount($user);

            if ($createdTodayProductCount >= $postLimit) {

                throw new PostLimitException;
            }

        }
    }

    /**
     * Set photo.
     *
     * @ORM\PrePersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function setPhoto(Post $entity, LifecycleEventArgs $args)
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request instanceof Request) {
            $em = $args->getEntityManager();

            $repository = $em->getRepository('AppBundle:Photo');

            $photoIds = $request->get('photos') ?: [];

            foreach ($photoIds as $photoId) {
                $photo = $repository->findOneById($photoId);

                if ($photo instanceof Photo) {
                    $entity->addPhoto($photo);
                    $photo->setPost($entity);
                    $photo->setIsUsed(1);

                    $em->persist($photo);
                }
            }
        }
    }

    /**
     * Set place.
     *
     * @ORM\PrePersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function setPlace(Post $entity, LifecycleEventArgs $args)
    {
        $places = null;

        $placeId = $entity->getPlaceId();

        $coords = $this->requestStack->getCurrentRequest()->request->get('coords');

        if ($placeId) {
            $places = $this->googleGeocodeService->getPlaceById($placeId, $args->getEntityManager());
        } elseif ($coords) {
            $places = $this->googleGeocodeService->getPlaceByCoords($coords, $args->getEntityManager());
        }

        if ($places) {
            foreach ($places as $key => $place) {
                $coords = $place[0]['geometry']['location'];
                $addressComponents = $place[0]['address_components'];
                $address = '';
                foreach ($addressComponents as $addressComponent) {
                    $address .= $addressComponent['long_name'] . ', ';
                }

                $places[$key] = substr($address, 0, -2);
            }

            $entity->setPlace($places);
        }

        if ($coords) {
            $entity->setLat($coords['lat']);
            $entity->setLng($coords['lng']);
        }
    }

    /**
     * Set meeting date.
     *
     * @ORM\PrePersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function setMeetingDate(Post $entity, LifecycleEventArgs $args)
    {
        $meetingDate = $entity->getMeetingDate();

        $day = new \DateTime($meetingDate['day']);
        $endOfRange = [
            'h' => date('H', strtotime($meetingDate['endOfRange'])),
            'i' => date('i', strtotime($meetingDate['endOfRange'])),
            's' => date('s', strtotime($meetingDate['endOfRange']))
        ];

        $startOfRange = [
            'h' => date('H', strtotime($meetingDate['startOfRange'])),
            'i' => date('i', strtotime($meetingDate['startOfRange'])),
            's' => date('s', strtotime($meetingDate['startOfRange']))
        ];

        $meetingDateStart = new \DateTime();
        $meetingDateStart->setDate(
            intval($day->format('Y')),
            intval($day->format('m')),
            intval($day->format('d'))
        );

        $meetingDateStart->setTime($startOfRange['h'], $startOfRange['i'],$startOfRange['s']);
        $entity->setMeetingDateStart($meetingDateStart);

        $meetingDateEnd = new \DateTime();
        $meetingDateEnd->setDate(
            intval($day->format('Y')),
            intval($day->format('m')),
            intval($day->format('d'))
        );

        $meetingDateEnd->setTime($endOfRange['h'], $endOfRange['i'], $endOfRange['s']);
        $entity->setMeetingDateEnd($meetingDateEnd);
    }
}
