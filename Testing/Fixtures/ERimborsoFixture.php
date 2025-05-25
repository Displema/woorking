<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Model\ERimborso;
use Model\ESegnalazione;

class ERimborsoFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 2; $i++) {
            $rimborso = new ERimborso();
            $rimborso
                ->setSegnalazione($this->getReference('ESegnalazione_' . $i, ESegnalazione::class))
                ->setImporto((int)$faker->randomFloat(2, 100));
            $manager->persist($rimborso);
            $this->addReference('ESegnalazione_' . $i, $rimborso);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            ESegnalazioneFixture::class,
        );
    }
}
