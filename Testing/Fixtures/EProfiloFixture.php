<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use \Model\EProfilo;

class EProfiloFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 20; $i++) {
            $user = new EProfilo();
            $user
                ->setNome($faker->firstName())
                ->setCognome($faker->lastName())
                ->setDataNascita($faker->dateTimeBetween("-60 years", "-18 years"))
                ->setIdUtente($faker->uuid())
                ->setIsAdmin(false)
                ->setTelefono($faker->phoneNumber());
            $manager->persist($user);
            $this->addReference('EProfilo_' . $i, $user);
        }
        $manager->flush();
    }
}