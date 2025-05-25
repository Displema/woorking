<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use \Model\EServiziAggiuntivi;
use Model\EUfficio;

class EServiziAggiuntiviFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 10; $i++) {
            $servizio = new EServiziAggiuntivi();
            $servizio->setNomeServizio($faker->word());
            $servizio->setUfficio($this->getReference('EUfficio_' . $i, EUfficio::class));

            $manager->persist($servizio);
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
