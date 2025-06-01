<?php
namespace Controller;
use DateTime;
use Model\Enum\FasciaOrariaEnum;
use Model\EPrenotazione;
use Model\EProfilo;
use Exception;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use View\Vmostrauffici;
use Model\ERecensione;
use Model\EUfficio;
use TechnicalServiceLayer\Foundation\FUfficio;

use TechnicalServiceLayer\Foundation\FEntityManager;
use View\VPrenotazioni;
use View\VRecensioni;


class COffice
{
    public  function Show($id,$date,$fascia)

    {


        $officephoto=[];
        $em = getEntityManager();
        $photoUrl=[];
        $office = $em->getRepository(EUfficio::class)->find($id);
        $photooffice = $em->getRepository(\Model\EFoto::class)->findBy(['ufficio' => $office->getId()]);
        foreach ($photooffice as $photo) {
            if ($photo) {
                $idPhoto = $photo->getId();
                $photoUrl[] = "/static/img/" . $idPhoto;

            }
        }


        $ufficiphoto[]=[
        'ufficio' => $office,
        'foto' => $photoUrl
    ];
        $view = new Vmostrauffici();
        $view->showOfficedetails($ufficiphoto,$date,$fascia);
    }

    public static function ShowReview($id){
        $em = getEntityManager();
        $review = [];
        $reporec=$em->getRepository(ERecensione::class);
        $review = $reporec->getRecensioneByUfficio($id);
        $office = $em->getRepository(EUfficio::class)->find($id);



        $view = new VRecensioni();
        $view->showAllRecension($review,$office);

    }

    public static function GetOffice($id){
        $em = getEntityManager();
        $office=$em->getRepository(EUfficio::class)->find($id);
        return $office ;
    }

    public function search()

    {
        $em = getEntityManager();
        $repo=$em->getRepository(EUfficio::class);
        $place=$_GET['place'];
        $date1=$_GET['date'];
        $fascia=$_GET['fascia'];





        $date = new DateTime($date1);
        $offices= $repo->findbythree($place,$date,$fascia);
        $photoEntity = [];
        foreach ($offices as $office) {
            $photoEntity = $em->getRepository(\Model\EFoto::class)->findOneBy(['ufficio' => $office->getId()]);

            if ($photoEntity) {
               $idPhoto = $photoEntity->getId();

               $photoUrl = $photoEntity ? "/static/img/" . $idPhoto : null;
                $photo = $photoUrl;
            }

            $officewithphoto[] = [
                'office' => $office,
                'photo' => $photo,
            ];
        }


      $view= new Vmostrauffici();
      $view->showuffici($officewithphoto,$date1,$fascia);

    }

    public function startsearch(){
        $view= new Vmostrauffici();
        $view->startsearch();
    }


    public function showconfirmedReservation($date,$idOffice,$fascia){
        $FasciaEnum=FasciaOrariaEnum::from($fascia);
        $em = getEntityManager();
        $em->beginTransaction();
        try {
            $office = $em->getRepository(EUfficio::class)->find($idOffice, \Doctrine\DBAL\LockMode::PESSIMISTIC_WRITE);
            $reservationCount = $em->getRepository(EPrenotazione::class)->CountByOfficeDateFascia($idOffice, $date, $fascia);
            echo $reservationCount;
            $placesAvaible = $office->getNumeroPostazioni();
            echo $placesAvaible;
            $uuid="1f091da3-ea4f-42d8-9277-04c7f19bb3fd";
            $utente=$em->getRepository(EProfilo::class)->find($uuid);

            if ($reservationCount >= $placesAvaible) {
                $view  = new VPrenotazioni();
                $view ->showplacenotavaible();
                exit;
            }
            $reservation = new EPrenotazione();
            $reservation->setData(new DateTime($date));
            $reservation->setUfficio($office);
            $reservation->setFascia($FasciaEnum);
            $reservation->setUtente($utente);

            $em->persist($reservation);
            $em->flush();
            $em->commit();

            $view = new Vmostrauffici();
            $view->showconfirmedpage1();
        } catch (Exception $e) {
            $em->rollback();
            echo $e->getMessage();
        }
    }
}