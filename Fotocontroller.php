
<?php

require_once 'vendor/autoload.php';
$em = // crea il tuo EntityManager

$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400);
    exit('ID mancante');
}

$foto=$em -> getRepository(\Model\EFoto::class)->findOneBy(["ufficio" => '09b7bee9-86a7-4bb9-a489-5a7fbf802e63'] );
if (!$foto) {
    http_response_code(404);
    exit('Foto non trovata');
}

header('Content-Type: image/jpeg');

echo stream_get_contents($foto->getContent());

