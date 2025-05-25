<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use \Model\EIndirizzo;

class EIndirizzoFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 20; $i++) {
            $indirizzo = new EIndirizzo();
            $indirizzo
                ->setCap($faker->postcode())
                ->setProvincia(strtoupper($faker->randomLetter() . $faker->randomLetter()))
                ->setCitta($faker->city())
                ->setVia($faker->streetAddress())
            ->setNumeroCivico($faker->randomDigit(2, true));
            $manager->persist($indirizzo);
            $this->addReference('EIndirizzo_' . $i, $indirizzo);
        }
        $manager->flush();
    }
}