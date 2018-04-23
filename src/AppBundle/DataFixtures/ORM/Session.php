<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class Session extends AbstractFixture implements ORMFixtureInterface, OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $session = new \Vidia\AuthBundle\Entity\Session();
        $session->setUser($this->getReference('admin'));
        $session->setExpirationDate(new \DateTime('+30 days'));
        $session->setOnline(new \DateTime());
        $session->setSessionKey('777');
        $session->setAccessToken('777');
        $session->setRefreshToken('777');
        $session->setOs('Linux');
        $session->setIp('127.0.0.1');

        $manager->persist($session);
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}
