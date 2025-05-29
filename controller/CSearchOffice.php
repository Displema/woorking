<?php
namespace Controller;
use DateTime;
use Model\EUfficio;
use TechnicalServiceLayer\Foundation\FUfficio;

use TechnicalServiceLayer\Foundation\FEntityManager;


class CSearchOffice {
    public static function search($luogo,$data,$fascia)

    {
        $em = FEntityManager::getInstance()->getEntityManager();
        $repo=$em->getRepository(EUfficio::class);
        //creation of dateobj Datetime with the date in input

        $date = new DateTime($data);
        $uffici = $repo->findbythree($luogo,$date,$fascia);
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
            return $ufficiConFoto;



    }
}// Inizializza Twig
