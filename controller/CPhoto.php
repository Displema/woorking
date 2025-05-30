<?php
namespace Controller;

use DateTime;
use Doctrine\ORM\EntityManager;
use Model\EFoto;
use Model\ESegnalazione;
use Model\EUfficio;
use PHP_CodeSniffer\Reports\Report;
use TechnicalServiceLayer\Foundation\FUfficio;

use TechnicalServiceLayer\Foundation\FEntityManager;

class CPhoto
{
    public static function view(string $id)
    {
        $em = getEntityManager()->getRepository(EFoto::class);
        $foto = $em->find($id);
        if (!$foto) {
            echo "Foto non trovato";
        }
        header("Content-Type: " . $foto->getMimeType());

        header("Content-Length: " . $foto->getSize());
        if (is_resource($foto->getContent())) {
            $content = stream_get_contents($foto->getContent());
        }

        if (empty($content)) {
            echo "404 not found";
        }

        echo $content;
    }
}
