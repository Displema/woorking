<?php
use TechnicalServiceLayer\Foundation\FUfficio;
use TechnicalServiceLayer\Foundation\FEntityManager;
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../bootstrap.php';



$em = FEntityManager::getInstance()->getEntityManager();

// Inizializza Twig
$loader = new \Twig\Loader\FilesystemLoader([
    __DIR__ . '/../html',  // vai su di un livello e poi html
    __DIR__               // oppure solo la cartella corrente View
]);
$twig = new \Twig\Environment($loader);

// Leggi parametri GET
$id = $_GET['id'] ?? '';


$Result = \controller\COffice::Show($id);
$ufficio = $Result[0];
echo $twig->render('/DettaglioOffice/DettaglioOffice.html.twig', ['ufficio' => $ufficio]);