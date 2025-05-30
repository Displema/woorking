<?php

require_once '../bootstrap.php';

use Model\EFoto;
use Ramsey\Uuid\Uuid;

$entityManager = getEntityManager();

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "ID mancante";
    exit;
}

$id = $_GET['id'];


try {
    $uuid = Uuid::fromString($id);

    $foto = $entityManager->getRepository(EFoto::class)->find($uuid);

    if (!$foto) {
        http_response_code(404);
        echo "Foto non trovata";
        exit;
    }

    $content = $foto->getContent();

    if (is_resource($content)) {
        $content = stream_get_contents($content);
    }



    header("Content-Type: " . $foto->getMimeType());
    header("Content-Length: " . strlen($content));

    echo $content;

} catch (Exception $e) {
    http_response_code(500);
    echo "Errore interno: " . $e->getMessage();
}
