<?php
namespace Controller;

use Controller;
use DateTime;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Model\EFoto;
use Model\EIndirizzo;
use Model\EIntervalloDisponibilita;
use Model\Enum\FasciaOrariaEnum;
use Model\Enum\ReportStateEnum;
use Model\Enum\StatoUfficioEnum;
use Model\EPrenotazione;
use Model\EProfilo;
use Exception;
use Model\EServiziAggiuntivi;
use TechnicalServiceLayer\Repository\EFotoRepository;
use TechnicalServiceLayer\Repository\EIntervalloDisponibilitaRepository;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use TechnicalServiceLayer\Repository\EServiziAggiuntiviRepository;
use TechnicalServiceLayer\Repository\UserRepository;
use TechnicalServiceLayer\Roles\Roles;
use View\VAdmin;
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
use View\VStatus;

class COffice extends BaseController
{
    public function show($id,$fascia ,$date ): void
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

        if ($this->auth_manager->isLoggedIn()) {
            $userId = $this->auth_manager->getUserId();
            $user =  $this->entity_manager->getRepository(EProfilo::class)->findoneBy(['user_id' => $userId]);
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
        $view->showOfficeDetails($officeDetails, $date, $fascia, $user);
    }



    public function ShowReview($id)
    {
        if ($this->auth_manager->isLoggedIn()) {
            $user = USession::requireUser();
        } else {
            $user = null;
        }

        $em = $this->entity_manager;
        $review = [];
        $reporec=$em->getRepository(ERecensione::class);
        $review = $reporec->getRecensioneByUfficio($id);
        $office = $em->getRepository(EUfficio::class)->find($id);

        $view = new VReview();
        $view->showAllReviews($review, $office, $user);
    }

    public function search(string $query, string $date, string $slot): void
    {
        if ($this->auth_manager->isLoggedIn()) {
            $user = USession::requireUser();
        } else {
            $user = null;
        }

        $userId = $this->auth_manager->getUserId();

        if (!($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }


        $repo=$this->entity_manager->getRepository(EUfficio::class);

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


        $photoEntity = [];
        foreach ($offices as $office) {
            if ($office->isHidden()) {
                continue;
            }
            $fasciaEnum = FasciaOrariaEnum::tryFrom($fascia);
            $reservationcount=$reservationRepo->getActiveReservationsByOfficeDateSlot($office, $date_parsed, $fasciaEnum);
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
        $view->showOfficeSearch($officewithphoto, $date, $slot, $user);
    }
    public function confirmReservation($date, $idOffice, $fascia)
    {    $em = $this->entity_manager;
        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }

        $userId = $this->auth_manager->getUserId();
        $user =  $em->getRepository(EProfilo::class)->findoneBy(['user_id' => $userId]);
        if (!($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }


        $date_parsed = new DateTime($date);
        $FasciaEnum=FasciaOrariaEnum::from($fascia);

        $em->beginTransaction();
        try {
            // With PessimisticWrite the first one that gets access to the office locks it until it's finished
            $office = $em->getRepository(EUfficio::class)->find($idOffice, LockMode::PESSIMISTIC_WRITE);
            $reservationCount = $em->getRepository(EPrenotazione::class)->getActiveReservationsByOfficeDateSlot($office, $date_parsed, $FasciaEnum);
            $placesAvaible = $office->getNumeroPostazioni();


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
            $view->showconfirmedpage1($user);
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
            $view->showStatus(404);
            return;
        }

        try {
            USession::requireAdmin();
        } catch (UserNotAuthenticatedException) {
            try {
                $auth = getAuth();
                if (!$auth->admin()->doesUserHaveRole($auth->getUserId(), Roles::LANDLORD)) {
                    $view = new VStatus();
                    $view->showStatus(403);
                    return;
                }
                $user = $this->entity_manager->getRepository(EProfilo::class)
                    ->findOneBy(['user_id'=>getAuth()->getUserId()]);

                if (!$user) {
                    $view = new VStatus();
                    $view->showStatus(403);
                }

                if ($user->getId() !== $office->getLocatore()->getId()) {
                    throw new UserNotAuthenticatedException();
                }
            } catch (UserNotAuthenticatedException) {
                error_log($office->getLocatore()->getId());
                $view = new VStatus();
                $view->showStatus(403);
                return;
            }
        }

        $office->setIsHidden(true);

        $reservations = $officeRepo->getActiveReservations($office);
        $refunds = 0;
        if ($shouldRefund && !$reservations->isEmpty()) {
            foreach ($reservations as $reservation) {
                $report = new ESegnalazione();
                $report->setUfficio($office)
                    ->setCommento("Rimborso per cancellazione ufficio")
                    ->setState(ReportStateEnum::class::SOLVED);
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

        $view = new VRedirect();
        $view->redirect('/office/'.$office->getId() . '/delete' . '/confirmation');
    }

    //search all bookings of the landlord's offices
    public static function showPrenotazioni()
    {
        $user = USession::requireUser();
        $id = $user->getId();
        $oggi = new \DateTime();
        $em = getEntityManager();

        $UfficiCompletiPassati = [];
        $UfficiCompletiPresenti = [];

        // Repositories
        $userRepo = UserRepository::getInstance();
        $uffici = $em->getRepository(EUfficio::class)->getOfficeByLocatore(['id' => $id]);
        $fotoRepo = $em->getRepository(Efoto::class);
        $prenotazioniRepo = $em->getRepository(EPrenotazione::class);
        $intervalliRepo = $em->getRepository(EIntervalloDisponibilita::class);

        try {
            foreach ($uffici as $ufficio) {
                $prenotazioni = $prenotazioniRepo->getPrenotazioneByUfficio($ufficio);
                if (empty($prenotazioni)) {
                    continue;
                }

                $intervallo = $intervalliRepo->getIntervallobyOffice($ufficio);
                $foto = $fotoRepo->getFirstPhotoByOffice($ufficio);
                $fotoUrl = $foto ? "/foto/" . $foto->getId() : null;

                $serviziObj = $ufficio->getServiziAggiuntivi();
                $servizi = [];
                foreach ($serviziObj as $s) {
                    $servizi[] = $s->getNomeServizio();
                }
                $serviziStringa = implode(', ', $servizi);

                // Per ogni prenotazione creo un oggetto singolo da passare al template
                foreach ($prenotazioni as $prenotazione) {
                    $data = $prenotazione->getData();
                    $email = $userRepo->getEmailByUserId($prenotazione->getUtente()->getUserId())[0]['email'];

                    $elemento = [
                        'ufficio' => $ufficio,
                        'email' => $email,
                        'foto' => $fotoUrl,
                        'prenotazioni' => $prenotazione,
                        'intervallo' => $intervallo,
                        'servizio' => $serviziStringa
                    ];

                    if ($data < $oggi) {
                        $UfficiCompletiPassati[] = $elemento;
                    } else {
                        $UfficiCompletiPresenti[] = $elemento;
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Errore nella gestione delle prenotazioni per ufficio: " . $e->getMessage());
        }

        // Call the view
        $view = new VOffice();
        $view->searchReservations($UfficiCompletiPassati, $UfficiCompletiPresenti);
    }



    //show all lessor's offices
    public static function showOfficesLocatore()
    {
        $user = USession::requireUser();
        $id = $user->getId();
        $UfficiCompleti = [];
        $em = getEntityManager();

        /** @var EUfficioRepository $uffici */
        $uffici = $em->getRepository(EUfficio::class)->getOfficeByLocatore(['id' => $id]);

        /** @var EFotoRepository $fotoRepo */
        $fotoRepo = $em->getRepository(Efoto::class);

        /** @var EServiziAggiuntiviRepository $serviziRepo */
        $serviziRepo = $em->getRepository(EServiziAggiuntivi::class);

        /** @var EIntervalloDisponibilitaRepository $intervalliRepo */
        $intervalliRepo = $em->getRepository(EIntervalloDisponibilita::class);

        foreach ($uffici as $ufficio) {
            $foto = $fotoRepo->getFirstPhotoByOffice($ufficio);
            $fotoUrl = $foto ? "/foto/" . $foto->getId() : null;
            $servizi = $serviziRepo->getServiziAggiuntivibyOffice($ufficio);
            $intervallo = $intervalliRepo->getIntervallobyOffice($ufficio);

            $UfficiCompleti[] = [
                'ufficio' => $ufficio,
                'foto' => $fotoUrl,
                'servizi' => $servizi,
                'intervallo' => $intervallo
            ];
        }

        $view = new VOffice();
        $view->searchOfficeLocatore($UfficiCompleti);
    }

    public function addOffice()
    {
        $view = new VOffice();
        $view->addOfficeV();
    }

    public function addOfficeInDB()
    {
        $em = getEntityManager();
        $ufficio = new EUfficio();
        $indirizzo = new Eindirizzo();
        $intervallo = new EIntervalloDisponibilita();

        $indirizzo->setProvincia($_POST['provincia']);
        $indirizzo->setCitta($_POST['comune']);
        $indirizzo->setCap($_POST['cap']);
        $indirizzo->setNumeroCivico($_POST['civico']);
        $indirizzo->setVia($_POST['indirizzo']);

        $ufficio->setTitolo($_POST['nome-ufficio']);
        $ufficio->setPrezzo($_POST['prezzo']);
        $ufficio->setDescrizione($_POST['descrizione']);
        $ufficio->setNumeroPostazioni($_POST['postazioni']);
        $ufficio->setSuperficie($_POST['superficie']);
        $ufficio->setDataCaricamento(new \DateTime());
        $ufficio->setStato(StatoUfficioEnum::InAttesa);
        $ufficio->setIndirizzo($indirizzo);

        $intervallo->setDataInizio(new \DateTime($_POST['data_inizio']));
        $intervallo->setDataFine(new \DateTime($_POST['data_fine']));
        $intervallo->setFascia(FasciaOrariaEnum::from($_POST['fascia']));
        $intervallo->setUfficio($ufficio);

        $em->persist($indirizzo);
        $em->persist($ufficio);
        $em->persist($intervallo);
        $em->flush();

        // Photos
        if (!empty($_FILES['foto']) && isset($_FILES['foto']['tmp_name'])) {
            foreach ($_FILES['foto']['tmp_name'] as $key => $tmpName) {
                if (is_uploaded_file($tmpName)) {
                    $content = file_get_contents($tmpName);
                    $mimeType = $_FILES['foto']['type'][$key];
                    $size = $_FILES['foto']['size'][$key];

                    $foto = new EFoto();
                    $foto->setContent($content);
                    $foto->setMimeType($mimeType);
                    $foto->setSize($size);
                    $foto->setUfficio($ufficio);

                    $em->persist($foto);
                }
            }
            //Salva tutte le foto in una volta sola
            $em->flush();
        }

        // Prendo i servizi dalle checkbox
        var_dump($_POST['servizi']);
        $listaServizi = $_POST['servizi'] ?? [];

        // Se è stato compilato "altro", aggiungo quel servizio
        if (!empty($_POST['altro-servizio'])) {
            $nomeAltro = trim($_POST['altro-servizio']);
            if ($nomeAltro !== '') {
                $listaServizi[] = $nomeAltro;
            }
        }

        // Salvo i servizi nel DB
        foreach ($listaServizi as $nomeServizio) {
            $servizio = new EServiziAggiuntivi();
            $servizio->setNomeServizio($nomeServizio);
            $servizio->setUfficio($ufficio);
            $em->persist($servizio);
        }
        $em->flush();
        header('Location: /uffici');
        exit;
    }

    public function showAdminOfficeDetails(string $id): void
    {
        $view = new VAdmin();
        try {
            $office = $this->entity_manager->find(EUfficio::class, $id);
        } catch (ORMException $e) {
            $view = new VStatus();
            $view->showStatus(500);
            return;
        }

        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        $view = new VAdmin();
        $userRepo = UserRepository::getInstance();
        $email = $userRepo->getEmailByUserId($office->getLocatore()->getUserId())[0]['email'];
        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        $reservations = $officeRepo->getActiveReservations($office);
        $view->showOfficeDetails($office, $email, count($reservations));
    }

    public function showPendingDetails(string $id): void
    {
        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
        }

        $userId = $this->auth_manager->getUserId();
        if ($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::BASIC_USER)) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }

        $office  = $this->entity_manager->find(EUfficio::class, $id);

        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        if ($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::ADMIN)) {
            $targetView = "showPendingAdmin";
        } else {
            $targetView = "showPendingLandlord";
        }

        $view = new VOffice();
        $view->$targetView($office);
    }


}
