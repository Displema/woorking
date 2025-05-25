<?php

use Model\ELocatore;
use TechnicalServiceLayer\Foundation\FEntityManager;
use TechnicalServiceLayer\Foundation\FUfficio;
use Model\EIndirizzo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';


$indirizzo_entity = new EIndirizzo();
$indirizzo_entity->setVia("Via le dita dal naso")
    ->setCap("12345")
    ->setCitta("Pescaraaaa")
    ->setProvincia("PE")
    ->setNumeroCivico("10");

try {
    FEntityManager::getInstance()->getEntityManager()->persist($indirizzo_entity);
} catch (\Doctrine\ORM\Exception\ORMException $e) {
    echo $e->getMessage();
}

try {
    $indirizzo_found = FEntityManager::getInstance()->getEntityManager()->find(EIndirizzo::class, Uuid::fromString("3dc7bb25-2323-47df-a453-51d7f9b5c90a"));
} catch (\Doctrine\ORM\Exception\ORMException $e) {
    echo $e->getMessage();
}

echo $indirizzo_found;
FEntityManager::getInstance()->getEntityManager()->flush();

$uffici = FUfficio::findByIndirizzoDataFascia($indirizzo, $fascia, $date);

if (empty($uffici)) {
    echo "Nessun ufficio trovato per i criteri specificati.\n";
} else {
    echo "Uffici trovati:\n";
    foreach ($uffici as $ufficio) {
        echo  $ufficio->getTitolo() . "\n";
    }
}
