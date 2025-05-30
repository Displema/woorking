<?php
namespace Controller;
use DateTime;
use Model\Enum\FasciaOrariaEnum;
use Model\EPrenotazione;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use View\Vmostrauffici;
use Model\ERecensione;
use Model\EUfficio;
use TechnicalServiceLayer\Foundation\FUfficio;

use TechnicalServiceLayer\Foundation\FEntityManager;
use View\VRecensioni;


class COffice
{
    public  function Show($id,$data,$fascia)

    {


        $ufficiphoto=[];
        $em = FEntityManager::getInstance()->getEntityManager();
        $fotoUrl=[];
        $ufficio = $em->getRepository(EUfficio::class)->find($id);
        $fotoUfficio = $em->getRepository(\Model\EFoto::class)->findBy(['ufficio' => $ufficio->getId()]);
        foreach ($fotoUfficio as $foto) {
            if ($foto) {
                $idPhoto = $foto->getId();
                $fotoUrl[] = "/static/img/" . $idPhoto;

            }
        }


        $ufficiphoto[]=[
        'ufficio' => $ufficio,
        'foto' => $fotoUrl
    ];
        $view = new Vmostrauffici();
        $view->showOfficedetails($ufficiphoto,$data,$fascia);
    }

    public static function ShowRecensioni($id){
        $em = FEntityManager::getInstance()->getEntityManager();
        $recensione = [];
        $reporec=$em->getRepository(ERecensione::class);
        $recensione = $reporec->getRecensioneByUfficio($id);
        $ufficio = $em->getRepository(EUfficio::class)->find($id);


        $view = new VRecensioni();
        $view->showAllRecension($recensione,$ufficio);

    }

    public static function GetOffice($id){
        $em = FEntityManager::getInstance()->getEntityManager();
        $office=$em->getRepository(EUfficio::class)->find($id);
        return $office ;
    }

    public function search()

    {
        $em = FEntityManager::getInstance()->getEntityManager();
        $repo=$em->getRepository(EUfficio::class);
        $luogo=$_GET['luogo'];
        $data=$_GET['data'];
        $fascia=$_GET['fascia'];





        $date = new DateTime($data);
        $uffici = $repo->findbythree($luogo,$date,$fascia);
        $ufficiConFoto = [];
        foreach ($uffici as $ufficio) {

            $fotoEntity = $em->getRepository(\Model\EFoto::class)->findOneBy(['ufficio' => $ufficio->getId()]);

            if ($fotoEntity) {
               $idPhoto = $fotoEntity->getId();

               $fotoUrl = $fotoEntity ? "/static/img/" . $idPhoto : null;
                $foto = $fotoUrl;
            }

            $ufficiConFoto[] = [
                'ufficio' => $ufficio,
                'fotoBase64' => $foto,
            ];
        }


      $view= new Vmostrauffici();
      $view->showuffici($ufficiConFoto,$data,$fascia);

    }

    public function startsearch(){
        $view= new Vmostrauffici();
        $view->startsearch();
    }


    public function showconfirmedPrenotation($idUfficio,$data,$fascia){
        $FasciaEnum=FasciaOrariaEnum::from($fascia);
        $em = FEntityManager::getInstance()->getEntityManager();
        $ufficio = $em->getRepository(EUfficio::class)->find($idUfficio);
        $prenotazione = new EPrenotazione();
        $prenotazione->setData(new DateTime($data));
        $prenotazione->setUfficio($ufficio);
        $prenotazione->setFascia($FasciaEnum);
       $prenotazionerepo=$em->getRepository(EPrenotazione::class);
       $prenotazionerepo->savePrenotation($prenotazione);
        $view= new Vmostrauffici();
       $view->showconfirmedpage1();
    }
}