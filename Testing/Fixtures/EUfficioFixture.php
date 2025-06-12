<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Model\Enum\StatoUfficioEnum;
use \Model\EUfficio;
use \Model\ELocatore;
use \Model\EIndirizzo;

class EUfficioFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 10; $i++) {
            $ufficio = new EUfficio();
            $ufficio
                ->setTitolo($faker->city())
                ->setDataApprovazione($faker->dateTimeBetween("-60 days", "-18 days"))
                ->setDescrizione($faker->text(100))
                ->setLocatore($this->getReference('ELocatore_' . $i, ELocatore::class))
                ->setPrezzo($faker->randomNumber(2, true))
                ->setNumeroPostazioni($faker->randomNumber(2, true))
                ->setSuperficie($faker->randomNumber(2, true))
                ->setDataCaricamento($faker->dateTimeBetween("-500 days", "-400 days"))
                ->setIndirizzo($this
                    ->getReference('EIndirizzo_' . $i, EIndirizzo::class))
                ->setStato(StatoUfficioEnum::Approvato);

            $manager->persist($ufficio);
            $this->addReference('EUfficio_' . $i, $ufficio);
        }

        for ($j = 0; $j < 10; $j++) {
            $office = new EUfficio();

            $stato = $faker->randomElement(
                [
                    StatoUfficioEnum::Nascosto,
                    StatoUfficioEnum::NonApprovato,
                    StatoUfficioEnum::InAttesa
                ]
            );

            $office
                ->setTitolo($faker->city())
                ->setDescrizione($faker->text(100))
                ->setLocatore($this->getReference('ELocatore_' . $j, ELocatore::class))
                ->setPrezzo($faker->randomNumber(2, true))
                ->setNumeroPostazioni($faker->randomNumber(2, true))
                ->setSuperficie($faker->randomNumber(2, true))
                ->setDataCaricamento($faker->dateTimeBetween("-500 days", "-400 days"))
                ->setIndirizzo($this
                    ->getReference('EIndirizzo_' . $j, EIndirizzo::class))
            ->setStato($stato);
            if ($stato === StatoUfficioEnum::NonApprovato) {
                $office->setDataRifiuto($faker->dateTimeBetween("-60 days", "-18 days"))
                    ->setMotivoRifiuto($faker->text(100));
            } elseif ($stato === StatoUfficioEnum::Nascosto) {
                $office->setDataApprovazione($faker->dateTimeBetween("-400 days", "-200 days"));
                $office->setDataCancellazione($faker->dateTimeBetween("-60 days", "-18 days"));
            }
            $this->setReference('EUfficio_' . $j + $i, $office);
            $manager->persist($office);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ELocatoreFixture::class,
        ];
    }
}
