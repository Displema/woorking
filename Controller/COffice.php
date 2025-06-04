<?php
namespace Controller;

use DateTime;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Model\EFoto;
use Model\Enum\FasciaOrariaEnum;
use Model\Enum\StatoUfficioEnum;
use Model\EPrenotazione;
use Model\EProfilo;
use Exception;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use View\VOffice;

use Model\ERecensione;
use Model\ERimborso;
use Model\ESegnalazione;
use Model\EUfficio;

use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use TechnicalServiceLayer\Utility\USession;
use View\VReservation;
use View\VReview;
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

    public function show($id, $date, $fascia): void
    {
        $officeDetails=[];
        $photoUrls=[];
        $login ='';
        $user ='';
        $office = $this->entity_manager->getRepository(EUfficio::class)->find($id);

        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }
        if (USession::isSetSessionElement('user')) {
            $utente = USession::requireUser();
            $login="isLoggedIn";
            $user = $this->entity_manager->getRepository(EProfilo::class)->find($utente->getId());
        }

        $photosRepo = $this->entity_manager->getRepository(EFoto::class);
        $photos = $photosRepo->findBy(['ufficio' => $office->getId()]);
        foreach ($photos as $photo) {
            if ($photo) {
                $photoUrls[] = '/static/img/' . $photo->getId();
            }
        }

        $officeDetails[]= [
        'ufficio' => $office,
        'foto' => $photoUrls
        ];
        $view = new VOffice();
        $view->showOfficeDetails($officeDetails, $date, $fascia,$user,$login);
    }

    public function confirmReservation($date, $idOffice, $fascia)
    {
        $FasciaEnum=FasciaOrariaEnum::from($fascia);
        $em = $this->entity_manager;
        $em->beginTransaction();
        try {
            // With PessimisticWrite the first one that gets access to the office locks it until it's finished
            $office = $em->getRepository(EUfficio::class)->find($idOffice, LockMode::PESSIMISTIC_WRITE);
            $reservationCount = $em->getRepository(EPrenotazione::class)->getActiveReservationsByOfficeDateSlot($idOffice, $date, $fascia);

            $placesAvaible = $office->getNumeroPostazioni();

            if (USession::isSetSessionElement('user')) {
                $utente = USession::requireUser();
                $login="isLoggedIn";
                $user = $em->getRepository(EProfilo::class)->find($utente->getId());
            } else {
                $view = new VRedirect();
                $view->redirect('/login');
                exit;
            }
            //$utente=$em->getRepository(EProfilo::class)->find($uuid);

            if ($reservationCount >= $placesAvaible) {
                $view  = new VReservation();
                $view ->showAlreadyBookedPage();
                exit;
            }
            $reservation = new EPrenotazione();
            $reservation->setData(new DateTime($date));
            $reservation->setUfficio($office);
            $reservation->setFascia($FasciaEnum);
            $reservation->setUtente($user);

            $em->persist($reservation);
            $em->flush();
            $em->commit();

            $view = new VOffice();
            $view->showconfirmedpage1($user,$login);
        } catch (Exception $e) {
            $em->rollback();
            echo $e->getMessage();
        }
    }

    public function ShowReview($id)
    {
        $em = $this->entity_manager;
        $review = [];
        $reporec=$em->getRepository(ERecensione::class);
        $review = $reporec->getRecensioneByUfficio($id);
        $office = $em->getRepository(EUfficio::class)->find($id);
        if (USession::isSetSessionElement('user')) {
            $utente = USession::requireUser();
            $login="isLoggedIn";
            $user = $em->getRepository(EProfilo::class)->find($utente->getId());
        }


        $view = new VReview();
        $view->showAllReviews($review, $office,$user,$login);
    }

    public function search(string $query, string $date, string $slot): void
    {
        $repo=$this->entity_manager->getRepository(EUfficio::class);
        $login='';
        $user='';
        $place = $query;
        try {
            $date_parsed = new DateTime($date);
        } catch (\Exception $e) {
            $view = new VStatus();
            $view->showStatus(400);
            return;
        }

        $fascia = $slot;
        $offices= $repo->findbythree($place, $date_parsed, $fascia);

        $officewithphoto=[];

        $reservationRepo=$this->entity_manager->getRepository(EPrenotazione::class);

        if (USession::isSetSessionElement('user')) {
            $login="isLoggedIn";
            $utente = USession::requireUser();

            $user = $this->entity_manager->getRepository(EProfilo::class)->find($utente->getId());
        }
        $photoEntity = [];
        foreach ($offices as $office) {
            if ($office->isHidden()) {
                continue;
            }
            $reservationcount=$reservationRepo->getActiveReservationsByOfficeDateSlot($office, $date_parsed, $fascia);
            $placeavaible= $office->getNumeroPostazioni();
            if ($reservationcount<$placeavaible) {
                $photoEntity = $this->entity_manager->getRepository(\Model\EFoto::class)->findOneBy(['ufficio' => $office->getId()]);

                if ($photoEntity) {
                    $idPhoto = $photoEntity->getId();

                    $photoUrl = $photoEntity ? "/static/img/" . $idPhoto : null;
                } else {
                    $photoUrl = "https://cdn.pixabay.com/photo/2024/07/20/17/12/warning-8908707_1280.png";
                }
            } else {
                continue;
            }
            $officewithphoto[] = [
                'office' => $office,
                'photo' => $photoUrl,

            ];
        }

        $view= new VOffice();
        $view->showOfficeSearch($officewithphoto, $date, $fascia,$user,$login);
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
        } catch (ORMException) {
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
        } catch (UserNotAuthenticatedException) {
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
            USession::requireAdmin();
        } catch (UserNotAuthenticatedException) {
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
                } catch (ORMException) {
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
        } catch (ORMException) {
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
