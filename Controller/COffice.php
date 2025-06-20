<?php
namespace Controller;

use DateTime;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Exception\ORMException;
use Model\EFoto;
use Model\EIndirizzo;
use Model\EIntervalloDisponibilita;
use Model\ELocatore;
use Model\Enum\FasciaOrariaEnum;
use Model\Enum\ReportStateEnum;
use Model\Enum\StatoUfficioEnum;
use Model\EPrenotazione;
use Exception;
use Model\EServiziAggiuntivi;
use TechnicalServiceLayer\Repository\EFotoRepository;
use TechnicalServiceLayer\Repository\EIntervalloDisponibilitaRepository;
use TechnicalServiceLayer\Repository\ELocatoreRepository;
use TechnicalServiceLayer\Repository\EServiziAggiuntiviRepository;
use TechnicalServiceLayer\Repository\UserRepository;
use TechnicalServiceLayer\Roles\Roles;
use View\VAdmin;
use View\VOffice;

use Model\ERimborso;
use Model\ESegnalazione;
use Model\EUfficio;

use TechnicalServiceLayer\Repository\EUfficioRepository;
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
            if ($office->getStato() === StatoUfficioEnum::Nascosto) {
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
        $this->entity_manager->beginTransaction();
        $office
            ->setMotivoRifiuto($reason)
            ->setStato(StatoUfficioEnum::NonApprovato)
            ->setDataRifiuto(new DateTime());

        try {
            $this->entity_manager->persist($office);
            $this->entity_manager->flush();
            $this->entity_manager->commit();
        } catch (ORMException) {
            if ($this->entity_manager->getConnection()->isTransactionActive()) {
                $this->entity_manager->rollback();
            }
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

        $office
            ->setStato(StatoUfficioEnum::Approvato)
            ->setDataApprovazione(new DateTime());
        $this->entity_manager->persist($office);
        $this->entity_manager->flush();

        $view = new VRedirect();
        $view->redirect('/admin/home');
    }

    public function deleteOffice(string $id, string $shouldRefund = '0'): void
    {
        $this->requireRoles([Roles::ADMIN, Roles::LANDLORD]);
        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        /** @var EUfficio $office */
        $office = $officeRepo->findOneBy(['id'=>$id]);

        if (!$office) {
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

        $this->entity_manager->beginTransaction();
        $this->entity_manager->lock($office, LockMode::PESSIMISTIC_READ);

        if ($office->getStato() === StatoUfficioEnum::Nascosto) {
            $this->entity_manager->flush();
            $this->entity_manager->commit();
            $view = new VStatus();
            $view->showStatus(400);
            return;
        }

        $office->setStato(StatoUfficioEnum::Nascosto);

        $reservations = $officeRepo->getActiveReservations($office);
        if ($shouldRefund && !$reservations->isEmpty()) {
            foreach ($reservations as $reservation) {
                $user = $office->getLocatore();
                $report = new ESegnalazione();
                $report->setUfficio($office)
                    ->setCommento("Rimborso per cancellazione ufficio")
                    ->setUser($user)
                    ->setState(ReportStateEnum::class::SOLVED)
                    ->setCreatedAt(new DateTime())
                    ->setUpdatedAt(new DateTime())
                    ->setCommentoAdmin("Rimborso per cancellazione ufficio");
                $refund = new ERimborso();
                $refund
                    ->setImporto($reservation->getPagamento()->getImporto())
                    ->setSegnalazione($report);

                try {
                    $this->entity_manager->persist($refund);
                    $this->entity_manager->persist($report);
                } catch (ORMException) {
                    if ($this->entity_manager->getConnection()->isTransactionActive()) {
                        $this->entity_manager->rollback();
                    }
                    $view = new VStatus();
                    $view->showStatus(500);
                }
                $this->entity_manager->flush();
            }
        }

        try {
            $this->entity_manager->persist($office);
            $this->entity_manager->flush();
            $this->entity_manager->commit();
        } catch (\Exception $e) {
            if ($this->entity_manager->getConnection()->isTransactionActive()) {
                $this->entity_manager->rollback();
            }
            $view = new VStatus();
            $view->showStatus(500);
        }

        $view = new VRedirect();
        $view->redirect('/home');
    }



    //show all lessor's offices
    public function showOfficesLocatore()
    {
        $this->requireRole(Roles::LANDLORD);

        $user = $this->getUser();
        $id = $user->getId();
        $ufficiApprovati = [];
        $ufficiNonApprovati = [];

        $em = getEntityManager();

        /** @var EUfficioRepository $ufficiRepo */
        $ufficiRepo = $em->getRepository(EUfficio::class);
        $uffici = $ufficiRepo->getOfficeByLocatore(['id' => $id]);

        /** @var EFotoRepository $fotoRepo */
        $fotoRepo = $em->getRepository(EFoto::class);

        /** @var EIntervalloDisponibilitaRepository $intervalliRepo */
        $intervalliRepo = $em->getRepository(EIntervalloDisponibilita::class);

        foreach ($uffici as $ufficio) {
            $foto = $fotoRepo->getFirstPhotoByOffice($ufficio);
            $fotoUrl = $foto ? "/static/img/" . $foto->getId() : null;
            $intervallo = $intervalliRepo->getIntervallobyOffice($ufficio);

            $datiUfficio = [
                'ufficio' => $ufficio,
                'foto' => $fotoUrl,
                'intervallo' => $intervallo
            ];

            if ($ufficio->getStato() === StatoUfficioEnum::Approvato) {
                $ufficiApprovati[] = $datiUfficio;
            } else if ($ufficio->getStato() === StatoUfficioEnum::NonApprovato)
            {
                $ufficiNonApprovati[] = $datiUfficio;
            }
        }

        $view = new VOffice();
        $view->searchOfficeLocatore($ufficiApprovati, $ufficiNonApprovati);
    }


    public function addOffice(): void
    {
        $this->requireRoles([Roles::LANDLORD, Roles::ADMIN]);

        if ($this->doesLoggedUserHaveRole(Roles::ADMIN)) {
            $view = new VRedirect();
            $view->redirect('/admin/home');
            return;
        }

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
        $servizi,
        $altro_servizio,
    ) {

        $this->requireRole(Roles::LANDLORD);

        $user = $this->getUser();

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
        $ufficio->setLocatore($user);

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
        $listaServizi = $servizi ?? [];

        // if there's the section "altro" I add it
        if ($altro_servizio !== null) {
            $nomeAltro = trim($altro_servizio);
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
        $view = new VRedirect();
        $view->redirect('/landlord/offices');
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
        $email = $userRepo->getEmailByUserId($office->getLocatore()->getUserId());
        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        $reservations = $officeRepo->getActiveReservations($office);


        $reservationsArray = $officeRepo->getReservationsByMonths($office);
        $grossStats = array_map(static function ($item) use ($office) {
            return $item * $office->getPrezzo();
        }, $reservationsArray);

        $view->showOfficeDetails($office, $email, count($reservations), $reservationsArray, $grossStats);
    }

    public function adminIndex(): void
    {
        $this->requireRole(Roles::ADMIN);
        $view = new VAdmin();

        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        $activeOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::Approvato);
        $pendingOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::InAttesa);
        $rejectedOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::NonApprovato);
        $hiddenOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::Nascosto);
        $view->showOfficesIndex($activeOffices, $pendingOffices, $rejectedOffices, $hiddenOffices);
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
        //$this->requireRoles([Roles::ADMIN, Roles::LANDLORD]);
        $this->requireRole(Roles::ADMIN);

        $office  = $this->entity_manager->find(EUfficio::class, $id);
        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        if ($office->getStato() !== StatoUfficioEnum::NonApprovato) {
            switch ($office->getStato()) {
                case StatoUfficioEnum::Approvato:
                    $view = new VRedirect();
                    $view->redirect("/admin/offices/$id");
                    break;
                case StatoUfficioEnum::InAttesa:
                    $view = new VRedirect();
                    $view->redirect("/admin/offices/pending/$id");
                    break;
                case StatoUfficioEnum::Nascosto:
                    $view = new VRedirect();
                    $view->redirect('/admin/home');
                    break;
            }
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

    public function saveAvailability(
        $office_id,
        $data_inizio,
        $data_fine,
        $fascia
    ): void
    {
        $this->requireRoles([Roles::LANDLORD, Roles::ADMIN]);
        $intervallo = new EIntervalloDisponibilita();
        $office = $this->entity_manager->find(EUfficio::class, $office_id);
        $intervallo->setDataInizio(new \DateTime($data_inizio));
        $intervallo->setDataFine(new \DateTime($data_fine));
        $intervallo->setFascia(FasciaOrariaEnum::from($fascia));
        $intervallo->setUfficio($office);

        // Validazione semplice
        if (!$office_id || !$data_fine || !$data_inizio || !$fascia) {
            $view = new VStatus();
            $view->showStatus(400);
            return;
        }

        // Recupera l'ufficio dal DB

        $office = $this->entity_manager->find(EUfficio::class, $office_id);
        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        try {
            $inizio = new \DateTime($data_inizio);
            $fine = new \DateTime($data_fine);
            if ($fine < $inizio) {
                throw new \Exception("Data fine precedente a data inizio");
            }
        } catch (\Exception $e) {
            $view = new VStatus();
            $view->showStatus(403);
            return;
        }

        // Validazione fascia oraria
        try {
            $fasciaEnum = FasciaOrariaEnum::from($fascia);
        } catch (\ValueError $ex) {
            $view = new VStatus();
            $view->showStatus(402);
            return;
        }

        // Crea nuovo intervallo disponibilità
        $intervallo = new EIntervalloDisponibilita();
        $intervallo->setDataInizio($inizio);
        $intervallo->setDataFine($fine);
        $intervallo->setFascia($fasciaEnum);
        $intervallo->setUfficio($office);

        $this->entity_manager->beginTransaction();
        try {
            $this->entity_manager->persist($intervallo);
            $this->entity_manager->flush();
            $this->entity_manager->commit();
        } catch (\Exception $e) {
            if ($this->entity_manager->getConnection()->isTransactionActive()) {
                $this->entity_manager->rollback();
            }
            $view = new VStatus();
            $view->showStatus(500);
            return;
        }

        // Redirect alla pagina dell'ufficio (o dove preferisci)
        $view = new VRedirect();
        $view->redirect('/landlord/offices');
    }



}
