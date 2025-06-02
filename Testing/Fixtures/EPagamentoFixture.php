<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Model\EPagamento;

class EPagamentoFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 10; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $pagamento = new EPagamento();
                $pagamento
                    ->setImporto((int) $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 10000));
                $manager->persist($pagamento);
                $this->addReference('EPagamento_' . $i . '_' . $j, $pagamento);
            }
        }
        $manager->flush();
    }
}