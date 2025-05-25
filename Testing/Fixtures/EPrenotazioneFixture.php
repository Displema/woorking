<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Model\EIntervalloDisponibilita;
use Model\EPagamento;
use \Model\EPrenotazione;
use Model\EProfilo;

class EPrenotazioneFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 10; $i++) {
            $intervallo = $this->getReference('EIntervallo_Disponibilita_' . $i, EIntervalloDisponibilita::class);
            $pagamento = $this->getReference('EPagamento_' . $i, EPagamento::class);
            $fascia = $intervallo->getFascia();
            $prenotazione = new EPrenotazione();
            $prenotazione
                ->setFascia($fascia)
                ->setData($faker->dateTimeBetween($intervallo->getDataInizio(), $intervallo->getDataFine()))
                ->setUfficio($intervallo->getUfficio())
                ->setUtente($this->getReference('EProfilo_' . $i, EProfilo::class))
                ->setPagamento($pagamento)
            ;

            $pagamento->setPrenotazione($prenotazione);

            $manager->persist($prenotazione);
            $this->addReference('EPrenotazione_' . $i, $prenotazione);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            EUfficioFixture::class,
            EIntervalliDisponibilitaFixture::class,
            EProfiloFixture::class,
        );
    }
}