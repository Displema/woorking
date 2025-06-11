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
use Model\ELocatore;
use Model\Enum\FasciaOrariaEnum;
use Model\Enum\ReportStateEnum;
use Model\Enum\StatoUfficioEnum;
use Model\EPrenotazione;
use Model\EProfilo;
use Exception;
use Model\EServiziAggiuntivi;
use TechnicalServiceLayer\Repository\EFotoRepository;
use TechnicalServiceLayer\Repository\EIntervalloDisponibilitaRepository;
use TechnicalServiceLayer\Repository\ELocatoreRepository;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use TechnicalServiceLayer\Repository\ERecensioneRepository;
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
    public function availability(string $id, string $slot, string $date): void
    {
        //check that the User is logged
        if ($this->isLoggedIn()) {
            $user = $this->getUser();
        } else {
            $user = null;
        }
        // take the repository of office with the entitymanager to have the access on DB
        $office = $this->entity_manager->getRepository(EUfficio::class)->find($id);

        //check if the office exist
        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }
        // call to the view to show the details of office
        $view = new VOffice();
        $view->showOfficeDetails($office, $date, $slot, $user);
    }

    public function searchResults(string $query, string $date, string $slot): void
    {
        // control on the login
        if ($this->isLoggedIn()) {
            $user = $this->getUser();
        } else {
            $user = null;
        }
        // this give the repo of Eufficio to use the methods to use db
        /** @var EUfficioRepository $repo */
        $repo=$this->entity_manager->getRepository(EUfficio::class);

        // conversion of date from string to datetime the we use in the method findbythree
        try {
            $date_parsed = new DateTime($date);
        } catch (Exception) {
            //if this not is possible there is an exception
            $view = new VStatus();
            $view->showStatus(400);
            return;
        }
        // to take the offices that had this 3 parameter (query,date,slot)
        $offices= $repo->findbythree($query, $date_parsed, $slot);

        //take the repository of Eprenotazione
        $reservationRepo=$this->entity_manager->getRepository(EPrenotazione::class);
        $validOffices=[];
        //is a foreach to access all offices
        foreach ($offices as $office) {
            if (($office->getStato()->value)!= "Approvato") {
                continue;
            }
            //conversion from string to FasciaOrariaEnum
            $slotEnum = FasciaOrariaEnum::tryFrom($slot);

            // with this we can take the number of reservation by office date e slot
            $reservationcount=$reservationRepo->getActiveReservationsByOfficeDateSlot($office, $date_parsed, $slotEnum);

            //place avaible for office
            $placeavaible= $office->getNumeroPostazioni();

            //check if the number of reservation for the office are minors than the place avaible
            if ($reservationcount>=$placeavaible) {
                continue;  // if the office is full we can pass to check another office
            }
            //it's a way to control that the office is hidden
            if ($office->isHidden()) {
                continue;
            }
            $validOffices[] = $office;
        }
        $view= new VOffice();
        $view->showOfficeSearch($validOffices, $date, $slot, $user);
    }


    public function rejectPending(string $id, string $reason): void
    {
        $this->requireRole(Roles::ADMIN);

        $repo = $this->entity_manager->getRepository(EUfficio::class);
        $office = $repo->find($id);
        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        if ($office->getStato()->value !== StatoUfficioEnum::InAttesa->value) {
            $view = new VStatus();
            $view->showStatus(400);
            return;
        }

        $office->setMotivoRifiuto($reason);
        $office->setStato(StatoUfficioEnum::NonApprovato);

        try {
            $this->entity_manager->persist($office);
            $this->entity_manager->flush();
        } catch (ORMException) {
            $view = new VStatus();
            $view->showStatus(500);
        }


        $view = new VRedirect();
        $view->redirect('/admin/home');
    }

    public function confirmPending(string $id): void
    {
        $this->requireRole(Roles::ADMIN);

        $repo = $this->entity_manager->getRepository(EUfficio::class);
        $office = $repo->find($id);

        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        if ($office->getStato()->value !== StatoUfficioEnum::InAttesa->value) {
            $view = new VStatus();
            $view->showStatus(400);
            return;
        }

        $office->setStato(StatoUfficioEnum::Approvato);
        $this->entity_manager->persist($office);
        $this->entity_manager->flush();

        $view = new VRedirect();
        $view->redirect('/admin/home');
    }

    public function deleteOffice(string $id, string $shouldRefund = '0'): void
    {
        $this->requireRoles([Roles::ADMIN, Roles::LANDLORD]);
        $this->entity_manager->beginTransaction();

        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        /** @var EUfficio $office */
        $office = $officeRepo->findOneBy(['id'=>$id]);

        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        $this->entity_manager->lock($office, LockMode::PESSIMISTIC_WRITE);



        if ($office->isHidden()) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        if ($this->doesLoggedUserHaveRole(Roles::LANDLORD)) {
            $user = $this->getUser();
            if ($office->getLocatore()->getId() !== $user->getId()) {
                $view = new VStatus();
                $view->showStatus(403);
                return;
            }
        }

        $office->setIsHidden(true);


        $reservations = $officeRepo->getActiveReservations($office);
        if ($shouldRefund && !$reservations->isEmpty()) {
            foreach ($reservations as $reservation) {
                $report = new ESegnalazione();
                $report->setUfficio($office)
                    ->setCommento("Rimborso per cancellazione ufficio")
                    ->setState(ReportStateEnum::class::SOLVED);
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
                $this->entity_manager->clear();
            }
        }

        try {
            $this->entity_manager->beginTransaction();
            $this->entity_manager->persist($office);
            $this->entity_manager->flush();
            $this->entity_manager->commit();
            $this->entity_manager->clear();
        } catch (\Exception $e) {
            $this->entity_manager->rollback();
            error_log("Errore durante il salvataggio di isHidden: " . $e->getMessage());
            $view = new VStatus();
            $view->showStatus(500);
        }

        $view = new VRedirect();
        $view->redirect('/office/'.$office->getId() . '/delete' . '/confirmation');
    }

    //search all bookings of the landlord's offices
    public function showPrenotazioni(): void
    {
        $this->requireLogin();

        $user = $this->getUser();
        $id = $user->getId();
        $oggi = new \DateTime();

        $UfficiCompletiPassati = [];
        $UfficiCompletiPresenti = [];

        // Repositories
        $userRepo = UserRepository::getInstance();
        $uffici = $this->entity_manager->getRepository(EUfficio::class)->getAllOfficeByLocatore(['id' => $id]);
        $fotoRepo = $this->entity_manager->getRepository(EFoto::class);
        $prenotazioniRepo = $this->entity_manager->getRepository(EPrenotazione::class);
        $intervalliRepo = $this->entity_manager->getRepository(EIntervalloDisponibilita::class);

        try {
            foreach ($uffici as $ufficio) {
                $prenotazioni = $prenotazioniRepo->getPrenotazioneByUfficio($ufficio);
                if (empty($prenotazioni)) {
                    continue;
                }

                $intervallo = $intervalliRepo->getIntervallobyOffice($ufficio);
                $foto = $fotoRepo->getFirstPhotoByOffice($ufficio);
                $fotoUrl = $foto ? "/static/img/"  . $foto->getId() : null;

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
    public function showOfficesLocatore()
    {
        $this->requireRole(Roles::LANDLORD);

        $user = $this->getUser();
        $id = $user->getId();
        $UfficiCompleti = [];
        $em = getEntityManager();

        /** @var EUfficioRepository $uffici */
        $uffici = $em->getRepository(EUfficio::class)->getOfficeByLocatore(['id' => $id]);

        /** @var EFotoRepository $fotoRepo */
        $fotoRepo = $em->getRepository(EFoto::class);

        /** @var EServiziAggiuntiviRepository $serviziRepo */
        $serviziRepo = $em->getRepository(EServiziAggiuntivi::class);

        /** @var EIntervalloDisponibilitaRepository $intervalliRepo */
        $intervalliRepo = $em->getRepository(EIntervalloDisponibilita::class);

        foreach ($uffici as $ufficio) {
            $foto = $fotoRepo->getFirstPhotoByOffice($ufficio);
            $fotoUrl = $foto ? "/static/img/" . $foto->getId() : null;
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

    public function addOffice(): void
    {
        $this->requireRole(Roles::LANDLORD);

        $view = new VOffice();
        $view->addOfficeV();
    }

    public function addOfficeInDB(
        $nome_ufficio,
        $provincia,
        $comune,
        $indirizzo,
        $civico,
        $cap,
        $prezzo,
        $superficie,
        $postazioni,
        $descrizione,
        $data_inizio,
        $data_fine,
        $fascia,
    ) {

        $this->requireRole(Roles::LANDLORD);

        $user = $this->getUser();
        /** @var ELocatoreRepository $locatore */
        $locatorerepo = getEntityManager()->getRepository(ELocatore::class);
        $locatore = $locatorerepo->getLocatoreByUser($user->getId());
        $ufficio = new EUfficio();
        $indirizzov = new Eindirizzo();
        $intervallo = new EIntervalloDisponibilita();

        $indirizzov->setProvincia($provincia);
        $indirizzov->setCitta($comune);
        $indirizzov->setCap($cap);
        $indirizzov->setNumeroCivico($civico);
        $indirizzov->setVia($indirizzo);

        $ufficio->setTitolo($nome_ufficio);
        $ufficio->setPrezzo($prezzo);
        $ufficio->setDescrizione($descrizione);
        $ufficio->setNumeroPostazioni($postazioni);
        $ufficio->setSuperficie($superficie);
        $ufficio->setDataCaricamento(new \DateTime());
        $ufficio->setStato(StatoUfficioEnum::InAttesa);
        $ufficio->setIndirizzo($indirizzov);
        $ufficio->setLocatore($locatore);

        $intervallo->setDataInizio(new \DateTime($data_inizio));
        $intervallo->setDataFine(new \DateTime($data_fine));
        $intervallo->setFascia(FasciaOrariaEnum::from($fascia));
        $intervallo->setUfficio($ufficio);

        $this->entity_manager->persist($indirizzov);
        $this->entity_manager->persist($ufficio);
        $this->entity_manager->persist($intervallo);
        $this->entity_manager->flush();

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

                    $this->entity_manager->persist($foto);
                }
            }
            //Saves all photos at once
            $this->entity_manager->flush();
        }

        // take the services from the checkbox
        $listaServizi = $_POST['servizi'] ?? [];

        // if there's the section "altro" I add it
        if (!empty($_POST['altro-servizio'])) {
            $nomeAltro = trim($_POST['altro-servizio']);
            if ($nomeAltro !== '') {
                $listaServizi[] = $nomeAltro;
            }
        }

        // Save on db
        foreach ($listaServizi as $nomeServizio) {
            $servizio = new EServiziAggiuntivi();
            $servizio->setNomeServizio($nomeServizio);
            $servizio->setUfficio($ufficio);
            $this->entity_manager->persist($servizio);
        }
        $this->entity_manager->flush();
        header('Location: /uffici');
        exit;
    }

    public function showAdminOfficeDetails(string $id): void
    {
        $this->requireRole(Roles::ADMIN);

        $office = $this->entity_manager->find(EUfficio::class, $id);
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
        $this->requireRoles([Roles::ADMIN, Roles::LANDLORD]);
        $office  = $this->entity_manager->find(EUfficio::class, $id);

        $user = $this->getUser();
        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        if ($this->doesLoggedUserHaveRole(Roles::LANDLORD) && $user->getId() !== $office->getLocatore()->getId()) {
            $view = new VStatus();
            $view->showStatus(403);
            return;
        }


        if ($this->doesLoggedUserHaveRole(Roles::ADMIN)) {
            $targetView = "showPendingAdmin";
        } else {
            $targetView = "showPendingLandlord";
        }

        $view = new VOffice();
        $view->$targetView($office);
    }

    public function showRejectedDetails(string $id): void
    {
        $this->requireRoles([Roles::ADMIN, Roles::LANDLORD]);
        $office  = $this->entity_manager->find(EUfficio::class, $id);
        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        $user = $this->getUser();

        if ($this->doesLoggedUserHaveRole(Roles::LANDLORD) && !($user->getId() === $office->getLocatore()->getUserId())) {
            $view = new VStatus();
            $view->showStatus(403);
            return;
        }

        if ($this->doesLoggedUserHaveRole(Roles::ADMIN)) {
            $targetView = "showRejectedAdmin";
        } else {
            $targetView = "showRejectedLandlord";
        }

        $view = new VOffice();
        $view->$targetView($office);
    }
}
