<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use \Model\EProfilo;
use Ramsey\Uuid\Type\Integer;

class EProfiloFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 20; $i++) {
            $user = new EProfilo();
            $user
                ->setName($faker->firstName())
                ->setSurname($faker->lastName())
                ->setDob($faker->dateTimeBetween("-60 years", "-18 years"))
                ->setUserId(FixtureState::$userIds[$i])
                ->setCreatedAt($faker->dateTimeBetween("-1 years", "-5 months"))
                ->setPhone($faker->phoneNumber());
            $manager->persist($user);
            $this->addReference('EProfilo_' . $i, $user);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            UserFixture::class,
        );
    }
}
