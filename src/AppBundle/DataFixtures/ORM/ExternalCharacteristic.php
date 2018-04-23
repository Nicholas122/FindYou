<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;

class ExternalCharacteristic extends AbstractFixture implements ORMFixtureInterface, OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $externalCharacteristicChoice = new \AppBundle\Entity\ExternalCharacteristic();

        $externalCharacteristicChoice->setName('choice');
        $externalCharacteristicChoice->setType('choice');

        $manager->persist($externalCharacteristicChoice);

        $externalCharacteristicChoiceInt = new \AppBundle\Entity\ExternalCharacteristic();

        $externalCharacteristicChoiceInt->setName('int');
        $externalCharacteristicChoiceInt->setType('int');

        $manager->persist($externalCharacteristicChoiceInt);

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
