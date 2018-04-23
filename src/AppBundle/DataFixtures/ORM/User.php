<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class User extends AbstractFixture implements ORMFixtureInterface, OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $user = new \AppBundle\Entity\User();

        $user->setIsVerified(1);
        $user->setFullName('Admin');
        $user->setUsername('admin');
        $user->setEmail('admin@gmail.com');
        $user->setDateOfBirth(new \DateTime());
        $user->setPlainPassword(123);
        $user->setRole('ROLE_SUPER_ADMIN');
        $user->setPhone('+380678309563');
        $user->setGender('male');
        $user->setLanguage($this->getReference('language'));

        $this->addReference('admin', $user);

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
