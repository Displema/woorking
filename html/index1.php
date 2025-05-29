<?php
use TechnicalServiceLayer\Foundation\FUfficio;

require_once 'C:\Users\39327\Desktop\UFFICI\vendor\autoload.php';
require_once 'C:\Users\39327\Desktop\UFFICI\bootstrap.php';
use Model\EUfficio;

//require_once 'C:\Users\39327\Desktop\UFFICI\TechnicalServiceLayer\Foundation\FEntityManager';
use TechnicalServiceLayer\Foundation\FEntityManager;

$em = FEntityManager::getInstance()->getEntityManager();
$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__ . '/homeaccess.html.twig'));
$twig = new \Twig\Environment($loader);
/*
$indirizzo = "Via Bacchisio 6";
$data = new DateTime("2025-06-15");
$fascia = "Mattina";
//$uffico = $em->getRepository(EUfficio::class)->findOneBy(['id'=>'735dde5c-fe8a-4df2-a0e8-338ea8abf702']);
$uffico = FUfficio::findby($indirizzo,$data,$fascia);
$uffici = $uffico;
$ufficiConFoto = [];

foreach ($uffici as $ufficio) {


    $fotoBlob = null;
    $fotoEntity = $em->getRepository(\Model\EFoto::class)->findOneBy(['ufficio' => $ufficio->getId()]);

    if ($fotoEntity) {
        $stream = $fotoEntity->getContent();  // Assumendo getBlob() ritorna uno stream o contenuto binario
        // Se è uno stream, leggi i dati binari
        if (is_resource($stream)) {
            $fotoData = stream_get_contents($stream);
        } else {
            $fotoData = $stream; // Se è una stringa binaria
        }

        // Codifica in base64
        $fotoBlob = 'data:image/jpeg;base64,' . base64_encode($fotoData);
    }

    $ufficiConFoto[] = [
        'ufficio' => $ufficio,
        'fotoBase64' => $fotoBlob,
    ];
}
*/
echo $twig->render('homeaccess.html.twig');