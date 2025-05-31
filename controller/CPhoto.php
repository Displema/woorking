<?php
namespace Controller;


use Exception;
use Model\EFoto;
use Ramsey\Uuid\Uuid;

class CPhoto
{
    public static function serveImage(string $id)
    {
        $entityManager = getEntityManager();

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
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo "Errore interno: " . $e->getMessage();
        }
    }
}
