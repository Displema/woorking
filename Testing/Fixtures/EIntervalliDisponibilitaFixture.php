<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Model\EIntervalloDisponibilita;
use Model\Enum\FasciaOrariaEnum;
use Model\EUfficio;

class EIntervalliDisponibilitaFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 10; $i++) {
            $intervallo = new EIntervalloDisponibilita();
            $intervallo
                ->setUfficio($this->getReference('EUfficio_' . $i, EUfficio::class))
                ->setFascia($faker->randomElement(FasciaOrariaEnum::class))
                ->setDataInizio($faker->dateTimeBetween('-120 days', '-60 days'))
                ->setDataFine($faker->dateTimeBetween('now', '+60 days'));
            $manager->persist($intervallo);
            $this->setReference('EIntervallo_Disponibilita_' . $i, $intervallo);
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