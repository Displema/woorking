<?php
namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use \Model\ELocatore;

class ELocatoreFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 10; $i++) {
            $user = new ELocatore();
            $user
                ->setNome($faker->firstName())
                ->setCognome($faker->lastName())
                ->setDataNascita($faker->dateTimeBetween("-60 years", "-18 years"))
                ->setIdUtente($faker->uuid())
                ->setIsAdmin(false)
                ->setPartitaIva($faker->randomNumber(2, true))
                ->setTelefono($faker->phoneNumber());

            $this->addReference('ELocatore_' . $i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
