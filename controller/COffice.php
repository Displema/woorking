<?php
namespace Controller;
use DateTime;
use Model\EFoto;
use Model\ERecensione;
use Model\EUfficio;
use controller\CPhoto;
use TechnicalServiceLayer\Foundation\FUfficio;

use TechnicalServiceLayer\Foundation\FEntityManager;


class COffice
{
    public static function Show($id)

    {
        $ufficiphoto=[];
        $em = FEntityManager::getInstance()->getEntityManager();
        $fotoList=[];
        $ufficio = $em->getRepository(EUfficio::class)->find($id);
        $fotoUfficio = $em->getRepository(\Model\EFoto::class)->findBy(['ufficio' => $ufficio->getId()]);
        foreach ($fotoUfficio as $foto) {
            $idPhoto = $foto->getId();
            $fotoUrl = $foto ? "/foto/" . $foto->getId() : null ;
            $fotoList[] = $fotoUrl;
        }
        $ufficiphoto[]=[
            'ufficio' => $ufficio,
            'foto' => $fotoList
        ];
        return $ufficiphoto;
    }

    public static function ShowRecensioni($id){
        $em = FEntityManager::getInstance()->getEntityManager();
        $recensione = [];
        $reporec=$em->getRepository(ERecensione::class);
        $recensione = $reporec->getRecensioneByUfficio($id);
        return $recensione;
    }

    public static function GetOffice($id){
        $em = FEntityManager::getInstance()->getEntityManager();
        $office=$em->getRepository(EUfficio::class)->find($id);
        return $office ;
    }

    public function search($luogo,$data,$fascia)

    {
        $em = FEntityManager::getInstance()->getEntityManager();
        $repo=$em->getRepository(EUfficio::class);
        //creation of dateobj Datetime with the date in input

        $date = new DateTime($data);
        $uffici = $repo->findbythree($luogo,$date,$fascia);
        $ufficiConFoto = [];
        foreach ($uffici as $ufficio) {
            $foto= null;
            $fotoEntity = $em->getRepository(\Model\EFoto::class)->findOneBy(['ufficio' => $ufficio->getId()]);

            if ($fotoEntity) {
                $idPhoto = $fotoEntity->getId();
                $fotoUrl = $fotoEntity ? "/static/img/{id}" . $fotoEntity->getId() : null ;
                $foto= $fotoUrl;
            }

            $ufficiConFoto[] = [
                'ufficio' => $ufficio,
                'fotoBase64' => $foto,
            ];
        }
        $view= new \Vmostrauffici();
        $view->showUffici($ufficiConFoto);



    }

}    /* if ($foto) {
                 $stream = $foto->getContent();
                 $fotoData = is_resource($stream) ? stream_get_contents($stream) : $stream;
                 $fotoBlob = 'data:image/jpeg;base64,' . base64_encode($fotoData);

             }}*/
// echo "Foto trovate: " . count($fotoUfficio); // <-- debug*/
