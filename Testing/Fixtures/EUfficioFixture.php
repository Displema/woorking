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
                ->setStato($faker->randomElement(StatoUfficioEnum::class))
                ->setNumeroPostazioni($faker->randomNumber(1, true))
                ->setSuperficie($faker->randomNumber(1, true))
                ->setDataCaricamento($faker->dateTimeBetween("-80 days", "-61 days"))
                ->setIndirizzo($this
                    ->getReference('EIndirizzo_' . $i, EIndirizzo::class));

            $manager->persist($ufficio);
            $this->addReference('EUfficio_' . $i, $ufficio);
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
