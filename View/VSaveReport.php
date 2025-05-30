<?php
use TechnicalServiceLayer\Foundation\FUfficio;
use TechnicalServiceLayer\Foundation\FEntityManager;

require_once 'C:\Users\39327\Desktop\UFFICI\vendor\autoload.php';
require_once 'C:\Users\39327\Desktop\UFFICI\bootstrap.php';


$em = FEntityManager::getInstance()->getEntityManager();

// Inizializza Twig
$loader = new \Twig\Loader\FilesystemLoader([
    __DIR__ . '/../html',  // vai su di un livello e poi html
    __DIR__               // oppure solo la cartella corrente View
]);
$twig = new \Twig\Environment($loader);

