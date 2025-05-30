<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Model\EPrenotazione;
use Model\ESegnalazione;
use Model\EUfficio;

class ESegnalazioneFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 5; $i++) {
            $segnalazione = new ESegnalazione();
            $segnalazione
                ->setCommento($faker->text($maxNbChars = 50))
                ->setUfficio($this->getReference('EUfficio_' . $i, EUfficio::class));
            $manager->persist($segnalazione);
            $this->addReference('ESegnalazione_' . $i, $segnalazione);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            EUfficioFixture::class,
        );
    }
}
