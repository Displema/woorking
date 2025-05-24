<?php
require_once __DIR__ .'/TechnicalServiceLayer/FIndirizzo.php';
require_once __DIR__ . '/TechnicalServiceLayer/FUfficio.php';
require_once __DIR__ . '/TechnicalServiceLayer/Foundation/FEntityManager.php';
use Woorking\TechnicalServiceLayer\Foundation\FEntityManager;
use TechnicalServiceLayer\FUfficio;
$indirizzo = "Via le dita dal naso";
$fascia = "MATTINA";

FEntityManager::getInstance();

$indirizzo = "Pescara";
$fascia = "Mattina";
$date = new DateTime("2025-05-23");

$uffici = FUfficio::findByIndirizzoDataFascia($indirizzo, $fascia, $date);

if (empty($uffici)) {
    echo "Nessun ufficio trovato per i criteri specificati.\n";
} else {
    echo "Uffici trovati:\n";
    foreach ($uffici as $ufficio) {
       
        echo  $ufficio->getTitolo() . "\n";
       
    }
}
