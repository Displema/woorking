<?php
namespace controller;
use DateTime;
use Model\EFoto;
use Model\ERecensione;
use Model\EUfficio;
use TechnicalServiceLayer\Foundation\FUfficio;

use TechnicalServiceLayer\Foundation\FEntityManager;


class CShowOffice
{
    public static function Show($id)

    {
        $ufficiphoto=[];
        $em = FEntityManager::getInstance()->getEntityManager();
        $fotoBase64List=[];
        $ufficio = $em->getRepository(EUfficio::class)->find($id);
        $fotoUfficio = $em->getRepository(\Model\EFoto::class)->findBy(['ufficio' => $ufficio->getId()]);
        foreach ($fotoUfficio as $foto) {

            if ($foto) {
                $stream = $foto->getContent();
                $fotoData = is_resource($stream) ? stream_get_contents($stream) : $stream;
                $fotoBlob = 'data:image/jpeg;base64,' . base64_encode($fotoData);
                $fotoBase64List[] = $fotoBlob;
            }




        }
        echo "Foto trovate: " . count($fotoUfficio); // <-- debug

        $ufficiphoto[]=[
        'ufficio' => $ufficio,
        'foto' => $fotoBase64List
    ];
        return $ufficiphoto;
    }

    public static function ShowRecensini($id){
        $em = FEntityManager::getInstance()->getEntityManager();
        $recensione = [];
        $reporec=$em->getRepository(ERecensione::class);
        $recensione = $reporec->getRecensioneByUfficio($id);
        return $recensione;
    }

}