<?php

namespace AppBundle\EventListener\DoctrineEntityListener;

use AppBundle\Entity\Photo;
use AppBundle\Service\Google\GoogleGeocodeService;
use AppBundle\Service\PhotoService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoEntityListener
{
    private $photoService;


    /**
     * @param PhotoService $photoService
     */
    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;

    }

    /**
     * Upload photo.
     *
     * @ORM\PrePersist()
     *
     * @param LifecycleEventArgs $args
     */
    public function upload(Photo $entity, LifecycleEventArgs $args)
    {
        $file = $entity->getPath();
        if (!$file instanceof UploadedFile) {
            return;
        }

        $path = $this->photoService->upload($file, $entity->getUser()->getId());

        $progressive = $this->photoService->saveProgressiveJPG($path);

        $entity->setPath($path);
        $entity->setProgressive($progressive);
    }


    /**
     * Remove photos.
     *
     * @ORM\PreRemove()
     *
     * @param LifecycleEventArgs $args
     */
    public function removePhotos(Photo $entity, LifecycleEventArgs $args)
    {
        $path = $entity->getPath();

        $this->deleteResizePhoto($path);
    }

    private function deleteResizePhoto($photo)
    {
        $photoDir = pathinfo($photo, PATHINFO_DIRNAME);
        $fileName = pathinfo($photo, PATHINFO_FILENAME);

        $files = scandir($photoDir);
        foreach ($files as $file) {
            if (0 === strpos($file, $fileName)) {
                unlink($photoDir . '/' . $file);
            }
        }
    }
}
