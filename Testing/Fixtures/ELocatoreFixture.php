<?php
namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use \Model\ELocatore;

class ELocatoreFixture extends AbstractFixture implements DependentFixtureInterface
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
                // +20 is required to not get normal users ids
                ->setIdUtente(FixtureState::$userIds[$i + 20])
                ->setIsAdmin(false)
                ->setPartitaIva($faker->randomNumber(9, true))
                ->setTelefono($faker->phoneNumber());

            $this->addReference('ELocatore_' . $i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            UserFixture::class
        );
    }
}
