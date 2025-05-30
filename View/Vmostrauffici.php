<?php

use TechnicalServiceLayer\Foundation\FEntityManager;
require_once 'C:\Users\39327\Desktop\UFFICI\vendor\autoload.php';
require_once 'C:\Users\39327\Desktop\UFFICI\bootstrap.php';
use \controller\COffice;
$em = FEntityManager::getInstance()->getEntityManager();

// Start Twig
$loader = new \Twig\Loader\FilesystemLoader([
    __DIR__ . '/../html',  // on the directory up
    __DIR__               // current directory of view
]);
$twig = new \Twig\Environment($loader);

// Read params GET
$luogo = $_GET['luogo'] ?? '';
$data = $_GET['data'] ?? '';
$fascia = $_GET['fascia'] ?? '';
//Call to  function to search office and show them
$Result = COffice::search($luogo,$data,$fascia);
    // Render Twig
    echo $twig->render('/uffici/uffici.html.twig', ['uffici' => $Result]);

