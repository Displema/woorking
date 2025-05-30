<?php
namespace Controller;

use DateTime;
use Doctrine\ORM\EntityManager;
use Model\Enum\FasciaOrariaEnum;
use Model\EPrenotazione;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use View\Vmostrauffici;
use Model\ERecensione;
use Model\EUfficio;
use View\VRecensioni;

class COffice
{
    private EntityManager $entity_manager;
    public function __construct()
    {
        $this->entity_manager = getEntityManager();
    }
    public function show($id, $data, $fascia): void
    {
        $ufficiphoto=[];
        $em = getEntityManager();
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

    public function showReviews($id)
    {
        $recensione = [];
        $repo=$this->entity_manager->getRepository(ERecensione::class);
        $recensione = $repo->getReviewByOffice($id);
        $ufficio = $this->entity_manager->getRepository(EUfficio::class)->find($id);


        $view = new VRecensioni();
        $view->showAllRecension($recensione, $ufficio);
    }

//    public function GetOffice($id)
//    {
//        return $this->entity_manager->getRepository(EUfficio::class)->find($id);
//    }

    public function search(string $place, string $date, string $fascia)
    {
        $repo=$this->entity_manager->getRepository(EUfficio::class);
//        $luogo=$_GET['luogo'];
//        $data=$_GET['data'];
//        $fascia=$_GET['fascia'];

        $date = new DateTime($date);
        $uffici = $repo->findbythree($place, $date, $fascia);
        $ufficiConFoto = [];
        foreach ($uffici as $ufficio) {
            $repository = $this->entity_manager->getRepository(\Model\EFoto::class);
            $fotoEntity = $repository->findOneBy(['ufficio' => $ufficio->getId()]);
            if ($fotoEntity) {
                $idPhoto = $fotoEntity->getId();
                $fotoUrl = "/static/img/$idPhoto";
                $foto = $fotoUrl;
            }

            $ufficiConFoto[] = [
                'ufficio' => $ufficio,
                'fotoBase64' => $foto,
            ];
        }


        $view= new Vmostrauffici();
        $view->showuffici($ufficiConFoto, $date, $fascia);
    }

    public function startSearch(): void
    {
        $view= new Vmostrauffici();
        $view->startsearch();
    }


    public function showReservationConfirm($idUfficio, $data, $fascia): void
    {
        $FasciaEnum=FasciaOrariaEnum::from($fascia);

        $ufficio = $this->entity_manager->getRepository(EUfficio::class)->find($idUfficio);
        $prenotazione = new EPrenotazione();
        $prenotazione
            ->setData(new DateTime($data))
            ->setUfficio($ufficio)
            ->setFascia($fascia);

        $this->entity_manager->persist($prenotazione);
        $this->entity_manager->flush();
        $view= new Vmostrauffici();
        $view->showconfirmedpage1();
    }
}
