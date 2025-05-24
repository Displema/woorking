<?php

use TechnicalServiceLayer\Foundation\FRecensione;


$idUfficio = "0e9b3283-37e4-11f0-8ec8-b48c9d833b56";




    $recensioni = FRecensione::getRecensioneByUfficio($idUfficio);

if (!empty($recensioni)) {
    foreach ($recensioni as $recensione) {
        echo $recensione->getCommento() . "<br>"; //
    }
} else {
    echo "Nessuna recensione trovata.";
}
