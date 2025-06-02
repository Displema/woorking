<?php
namespace Controller;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Model\Enum\FasciaOrariaEnum;
use Model\Enum\StatoUfficioEnum;
use Model\EPrenotazione;
use Model\EProfilo;
use Exception;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use View\Vmostrauffici;

use Model\ERecensione;
use Model\ERimborso;
use Model\ESegnalazione;
use Model\EUfficio;

use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use TechnicalServiceLayer\Utility\USession;


use TechnicalServiceLayer\Foundation\FUfficio;

use TechnicalServiceLayer\Foundation\FEntityManager;
use View\VPrenotazioni;

use View\VRecensioni;
use View\VRedirect;
use View\VResource;
use View\VStatus;

class COffice
{

    private EntityManager $entity_manager;
    public function __construct()
    {
        $this->entity_manager = getEntityManager();
    }

    public  function Show($id,$date,$fascia)

    {


        $officephoto=[];
        $em = $this->entity_manager;
        $photoUrl=[];
        $office = $em->getRepository(EUfficio::class)->find($id);
        $photooffice = $em->getRepository(\Model\EFoto::class)->findBy(['ufficio' => $office->getId()]);
        foreach ($photooffice as $photo) {
            if ($photo) {
                $idPhoto = $photo->getId();
                $photoUrl[] = "/static/img/" . $idPhoto;


            }
        }


        $officephoto[]=[

        'ufficio' => $office,
        'foto' => $photoUrl
        ];
        $view = new Vmostrauffici();
        $view->showOfficedetails($officephoto, $date, $fascia);
    }



    public function GetOffice($id)
    {
        $em = $this->entity_manager;
        return $em->getRepository(EUfficio::class)->find($id);
    }

    public  function ShowReview($id){
        $em = $this->entity_manager;
        $review = [];
        $reporec=$em->getRepository(ERecensione::class);
        $review = $reporec->getRecensioneByUfficio($id);
        $office = $em->getRepository(EUfficio::class)->find($id);



        $view = new VRecensioni();
        $view->showAllRecension($review,$office);

    }



    public function search(string $query, string $date, string $slot)

    {
        $em = $this->entity_manager;
        $repo=$em->getRepository(EUfficio::class);
       // $place=$_GET['place'];
        //$date1=$_GET['date'];
        //$fascia=$_GET['fascia'];


        $place= $query;
        $date_parsed = new DateTime($date);
        $fascia= $slot;
        $offices= $repo->findbythree($place,$date_parsed,$fascia);
        $officewithphoto=[];
        $reporeservaton=$em->getRepository(EPrenotazione::class);
        $photoEntity = [];
        foreach ($offices as $office) {
            if ($office->isHidden()){
            continue;
        }
            $reservationcount=$reporeservaton->CountByOfficeDateFascia($office,$date_parsed,$fascia);
            $placeavaible= $office->getNumeroPostazioni();
            if($reservationcount<$placeavaible){


            $photoEntity = $em->getRepository(\Model\EFoto::class)->findOneBy(['ufficio' => $office->getId()]);

            if ($photoEntity) {
               $idPhoto = $photoEntity->getId();

               $photoUrl = $photoEntity ? "/static/img/" . $idPhoto : null;

            }else {
                $photoUrl = "https://cdn.pixabay.com/photo/2024/07/20/17/12/warning-8908707_1280.png";
            }
        } else{
                continue;

            }
            $officewithphoto[] = [
                'office' => $office,
                'photo' => $photoUrl,

            ];
        }

      $view= new Vmostrauffici();
      $view->showuffici($officewithphoto,$date,$fascia);


    }

    public function startsearch()
    {
        $view= new Vmostrauffici();
        $view->startsearch();
    }



    public function showconfirmedReservation($date,$idOffice,$fascia){
        $FasciaEnum=FasciaOrariaEnum::from($fascia);
        $em = $this->entity_manager;
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

    public function rejectPending(string $id, string $reason): void
    {
        try {
            $user = USession::requireAdmin();
        } catch (UserNotAuthenticatedException $e) {
            $view = new VStatus();
            $view->showStatus(403);
            return;
        }

        $repo = $this->entity_manager->getRepository(EUfficio::class);
        $office = $repo->find($id);
        if (!$office) {
            //TODO: show bad request
        }

        if (StatoUfficioEnum::tryFrom(!$office->getStato()) === StatoUfficioEnum::InAttesa) {
            // TODO: show bad request
        }

        $office->setReason($reason);
        $office->setStato(StatoUfficioEnum::NonApprovato);

        try {
            $this->entity_manager->persist($office);
            $this->entity_manager->flush();
        } catch (ORMException $e) {
            $view = new VStatus();
            $view->showStatus(500);
        }


        $view = new VRedirect();
        // TODO: remove placeholder string and add correct view
        $view->redirect('/rejectedoffice');
    }

    public function confirmPending(string $id): void
    {
        try {
            $user = USession::requireAdmin();
        } catch (UserNotAuthenticatedException $e) {
            $view = new VStatus();
            $view->showStatus(403);
            return;
        }

        $repo = $this->entity_manager->getRepository(EUfficio::class);
        $office = $repo->find($id);
        if (!$office) {
            //TODO: like on reject
        }

        if (StatoUfficioEnum::tryFrom(!$office->getStato()) === StatoUfficioEnum::InAttesa) {
            // TODO: show bad request
        }

        $office->setStato(StatoUfficioEnum::Approvato);
        $this->entity_manager->persist($office);
        $this->entity_manager->flush();

        $view = new VRedirect();
        $view->redirect('/approvedoffice');
    }

    public function deleteOffice(string $id, string $shouldRefund = '0'): void
    {
        try {
            $user = USession::requireAdmin();
        } catch (UserNotAuthenticatedException $e) {
            $view = new VStatus();
            $view->showStatus(403);
            return;
        }

        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        $office = $officeRepo->findOneBy(['id'=>$id]);

        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        if ($office->isHidden()) {
            $view = new VStatus();
            $view->showStatus(403);
            return;
        }

        $office->setIsHidden(true);

        $reservations = $officeRepo->getActiveReservations($office);
        $refunds = 0;
        if ($shouldRefund && !$reservations->isEmpty()) {
            foreach ($reservations as $reservation) {
                $report = new ESegnalazione();
                $report->setUfficio($office)
                    ->setCommento("Rimborso per cancellazione ufficio");

                $refunds++;
                $refund = new ERimborso();
                $refund
                    ->setImporto($reservation->getPagamento()->getImporto())
                    ->setSegnalazione($report);

                try {
                    $this->entity_manager->beginTransaction();
                    $this->entity_manager->persist($refund);
                    $this->entity_manager->persist($report);
                    $this->entity_manager->flush();
                } catch (ORMException $e) {
                    $view = new VStatus();
                    $view->showStatus(500);
                }

                $this->entity_manager->commit();
            }
        }

        try {
            $this->entity_manager->beginTransaction();
            $this->entity_manager->persist($office);
            $this->entity_manager->flush();
            $this->entity_manager->commit();
        } catch (ORMException $e) {
            $view = new VStatus();
            $view->showStatus(500);
        }

        $view = new VResource();
        $view->printJson(
            json_encode(array(
                'reservations' => $reservations,
                'refunds' => $refunds,
            ), JSON_THROW_ON_ERROR)
        );
    }
}
