<?php

require_once 'C:\Users\Lenovo\Desktop\woorking\vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('home.html.twig', [
    'title' => 'Pagina Twig',
    'message' => 'Benvenuto nel template Twig!'
]);