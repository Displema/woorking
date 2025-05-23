

<?php
require_once __DIR__ .'/TechnicalServiceLayer/FIndirizzo.php';
require_once __DIR__ . '/TechnicalServiceLayer/FUfficio.php';
require_once __DIR__ . '/TechnicalServiceLayer/foundation/FEntityManager.php';
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
        // Presumo che EUfficio abbia un metodo __toString o almeno getId/getNome
        echo  $ufficio->getTitolo() . "\n";
        // oppure echo $ufficio; se hai __toString
    }
}
