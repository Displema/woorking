<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Model\Enum\ReportStateEnum;
use Model\EPrenotazione;
use Model\EProfilo;
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
                ->setUfficio($this->getReference('EUfficio_' . $i, EUfficio::class))
                ->setUser($this->getReference('EProfilo_' . $i, EProfilo::class))
                ->setState(ReportStateEnum::ACTIVE)
                ->setCreatedAt($faker->dateTimeBetween('-60 days', '-10 days'));
            $manager->persist($segnalazione);
            $this->addReference('ESegnalazione_' . $i, $segnalazione);
        }

        for ($j = 0; $j < 5; $j++) {
            $segnalazione = new ESegnalazione();
            $segnalazione
                ->setCommento($faker->text($maxNbChars = 50))
                ->setUfficio($this->getReference('EUfficio_' . $j, EUfficio::class))
                ->setUser($this->getReference('EProfilo_' . $j, EProfilo::class))
                ->setState(ReportStateEnum::SOLVED)
                ->setCommentoAdmin($faker->text($maxNbChars = 250))
                ->setCreatedAt($faker->dateTimeBetween('-60 days', '-10 days'))
                ->setUpdatedAt($faker->dateTimeBetween('-10 days', '-5 days'));
            $manager->persist($segnalazione);
            $this->addReference('ESegnalazione_' . $j + $i, $segnalazione);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            EUfficioFixture::class,
            EProfiloFixture::class,
        );
    }
}
