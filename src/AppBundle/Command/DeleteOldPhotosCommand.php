<?php

namespace AppBundle\Command;

use AppBundle\Entity\Photo;
use AppBundle\Repository\PhotoRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteOldPhotosCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:delete:old-photos')
            ->setDescription('Delete old photos')
            ->setHelp('This console command delete the unused photos');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /**
         * @var PhotoRepository $repository
         */
        $repository = $em->getRepository('AppBundle:Photo');

        $oldPhotos = $repository->removeOldPhotos();

        $output->writeln(
            sprintf(
                'Removed %s old photos',
                $oldPhotos)
        );
    }
}