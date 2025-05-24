<?php
require_once __DIR__ .'/TechnicalServiceLayer/foundation/FEntityManager.php';
require_once __DIR__ ."/TechnicalServiceLayer/FRecensione.php";
require_once __DIR__ ."/Entity/EUfficio.php";


$idUfficio = "0e9b3283-37e4-11f0-8ec8-b48c9d833b56"
; 




    $recensioni = FRecensione::getRecensioneByUfficio($idUfficio);

    if (!empty($recensioni)) {
        foreach ($recensioni as $recensione) {
            echo $recensione->getCommento() . "<br>"; // 
        }
    } else {
        echo "Nessuna recensione trovata.";
    }

