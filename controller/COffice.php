<?php
namespace Controller;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Model\Enum\FasciaOrariaEnum;
use Model\Enum\StatoUfficioEnum;
use Model\EPrenotazione;
use Model\ERecensione;
use Model\ERimborso;
use Model\ESegnalazione;
use Model\EUfficio;
use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use TechnicalServiceLayer\Utility\USession;
use View\Vmostrauffici;
use View\VRecensioni;
use View\VRedirect;
use View\VResource;
use View\VStatus;

class COffice
{
    private EntityManager $entityManager;
    public function __construct()
    {
        $this->entity_manager = getEntityManager();
    }
    public function Show($id, $data, $fascia)
    {
        $ufficiphoto=[];
        $em = $this->entity_manager;
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
        $view->showOfficedetails($ufficiphoto, $data, $fascia);
    }

    public function ShowRecensioni($id)
    {
        $em = $this->entity_manager;
        $recensione = [];
        $repo=$em->getRepository(ERecensione::class);
        $recensione = $repo->getRecensioneByUfficio($id);
        $ufficio = $em->getRepository(EUfficio::class)->find($id);


        $view = new VRecensioni();
        $view->showAllRecension($recensione, $ufficio);
    }

    public function GetOffice($id)
    {
        $em = $this->entity_manager;
        return $em->getRepository(EUfficio::class)->find($id);
    }

    public function search(string $query, string $date, string $slot)
    {
        $em = $this->entity_manager;
        $repo=$em->getRepository(EUfficio::class);
//        $luogo=$_GET['luogo'];
//        $data=$_GET['data'];
//        $fascia=$_GET['fascia'];

        $luogo= $query;
        $date_parsed = new DateTime($date);
        $fascia= $slot;

        $uffici = $repo->findbythree($luogo, $date_parsed, $fascia);
        $ufficiConFoto = [];
        foreach ($uffici as $ufficio) {
            $fotoEntity = $em->getRepository(\Model\EFoto::class)->findOneBy(['ufficio' => $ufficio->getId()]);

            if ($fotoEntity) {
                $idPhoto = $fotoEntity->getId();
                $fotoUrl = "/static/img/$idPhoto";
            } else {
                $fotoUrl = "https://cdn.pixabay.com/photo/2024/07/20/17/12/warning-8908707_1280.png";
            }

            $ufficiConFoto[] = [
                'ufficio' => $ufficio,
                'fotoBase64' => $fotoUrl,
            ];
        }


        $view= new Vmostrauffici();
        $view->showuffici($ufficiConFoto, $date_parsed, $fascia);
    }

    public function startsearch()
    {
        $view= new Vmostrauffici();
        $view->startsearch();
    }


    public function showconfirmedPrenotation($idUfficio, $data, $fascia)
    {
        $FasciaEnum=FasciaOrariaEnum::from($fascia);
        $em = $this->entity_manager;
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
