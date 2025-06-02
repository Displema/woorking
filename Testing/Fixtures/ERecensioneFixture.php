<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Model\EPrenotazione;
use Model\ERecensione;

class ERecensioneFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 10; $i++) {
            $recensione = new ERecensione();
            $recensione
                ->setCommento($faker->text($maxNbChars = 50))
                ->setValutazione($faker->randomDigit())
                ->setPrenotazione($this->getReference('EPrenotazione_' . $i . '_0', EPrenotazione::class));
            $manager->persist($recensione);
            $this->addReference('ERecensione_' . $i, $recensione);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            EPrenotazioneFixture::class,
        );
    }
}