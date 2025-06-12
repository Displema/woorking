<?php
namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use \Model\ELocatore;

class ELocatoreFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 10; $i++) {
            $user = new ELocatore();
            $user
                ->setName($faker->firstName())
                ->setSurname($faker->lastName())
                ->setDob($faker->dateTimeBetween("-60 years", "-18 years"))
                // +20 is required to not get normal users ids
                ->setUserId(FixtureState::$userIds[$i + 20])
                ->setPartitaIva($faker->randomNumber(9, true))
                ->setCreatedAt($faker->dateTimeBetween("-1 years", "-5 months"))
                ->setPhone($faker->phoneNumber());

            $this->addReference('ELocatore_' . $i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            UserFixture::class
        );
    }
}
