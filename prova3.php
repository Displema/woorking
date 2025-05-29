<?php

require_once 'C:\Users\39327\Desktop\UFFICI\vendor\autoload.php';
require_once 'C:\Users\39327\Desktop\UFFICI\bootstrap.php';

use TechnicalServiceLayer\Foundation\FUfficio;

// Parametri di ricerca
$indirizzo = "Strada Manuela 34 Appartamento 44";
$data = new DateTime("2025-06-15");
$fascia = "Mattina"; // Supponendo che sia una stringa, altrimenti usa enum

// Chiamata alla funzione
$uffici = FUfficio::findby($indirizzo, $data, $fascia);

if (empty($uffici)) {
    echo "Nessun ufficio trovato.\n";
} else {
    foreach ($uffici as $ufficio) {
        echo "TITOLO: " . $ufficio->getTitolo() . "\n";
        echo "INDIRIZZO: " . $ufficio->getIndirizzo()->getVia() . "\n";
        echo "PREZZO: " . $ufficio->getPrezzo() . "\n";
        echo "POSTAZIONI: " . $ufficio->getNumeroPostazioni() . "\n";
        echo "-------------------------\n";
    }
}
