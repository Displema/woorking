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

// Leggi parametri GET
$luogo = $_GET['luogo'] ?? '';
$data = $_GET['data'] ?? '';
$fascia = $_GET['fascia'] ?? '';

if ($luogo && $data && $fascia) {
    $dateObj = new DateTime($data);

    // Cerca uffici
    $uffici = FUfficio::findby($luogo, $dateObj, $fascia);

    // Aggiungi foto base64
    $ufficiConFoto = [];
    foreach ($uffici as $ufficio) {
        $fotoBlob = null;
        $fotoEntity = $em->getRepository(\Model\EFoto::class)->findOneBy(['ufficio' => $ufficio->getId()]);

        if ($fotoEntity) {
            $stream = $fotoEntity->getContent();
            $fotoData = is_resource($stream) ? stream_get_contents($stream) : $stream;
            $fotoBlob = 'data:image/jpeg;base64,' . base64_encode($fotoData);
        }

        $ufficiConFoto[] = [
            'ufficio' => $ufficio,
            'fotoBase64' => $fotoBlob,
        ];
    }

    // Render Twig
    echo $twig->render('/uffici.html.twig', ['uffici' => $ufficiConFoto]);
} else {
    echo "Parametri mancanti.";
}
