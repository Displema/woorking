<?php
namespace Controller;
use DateTime;
use Model\EFoto;
use Model\EIntervalloDisponibilita;
use Model\EPrenotazione;
use Model\EServiziAggiuntivi;
use Model\EUfficio;
use TechnicalServiceLayer\Foundation\FEntityManager;
use View\VLocatore;
use View\VUffici;

class CSearchOffice {



    public function __construct() {
    }
    public static function search($luogo,$data,$fascia)

    {
        $em = FEntityManager::getInstance()->getEntityManager();
        $repo=$em->getRepository(EUfficio::class);
        //creation of dateobj Datetime with the date in input

        $date = new DateTime($data);
        $uffici = $repo->findbythree($luogo,$date,$fascia);
        $ufficiConFoto = [];
        foreach ($uffici as $ufficio) {
            $fotoBlob = null;
            $fotoEntity = $em->getRepository(\Model\EFoto::class)->findOneBy(['ufficio' => $ufficio->getId()]);

            if ($fotoEntity) {
                $stream = $fotoEntity->getContent();
                $fotoData = is_resource($stream) ? stream_get_contents($stream) : $stream;
                $fotoBlob = 'data:image/jpeg;base64,' . base64_encode($fotoData);
            }

            $ufficiConFoto[] = [
                'ufficio' => $ufficio,
                'fotoBase64' => $fotoBlob,
            ];
        }
            return $ufficiConFoto;



    }


    public static function showOfficesLocatore() {

        $UfficiCompleti = [];
        $em = getEntityManager();
        $uffici = $em->getRepository(EUfficio::class)->findAll();
        $fotoRepo = $em->getRepository(Efoto::class);
        $serviziRepo = $em->getRepository(EServiziAggiuntivi::class);
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

        $view = new VUffici();
        $view->searchOffice($UfficiCompleti);

    }

    public static function showPrenotazioni(){
        $oggi = new \DateTime();
        $em = FEntityManager::getInstance()->getEntityManager();
        $UfficiCompletiPassati=[];
        $UfficiCompletiPresenti=[];



        $uffici = $em->getRepository(EUfficio::class)->findAll();
        $fotoRepo = $em->getRepository(Efoto::class);
        $prenotazioniRepo = $em->getRepository(EPrenotazione::class);
        $intervalliRepo = $em->getRepository(EIntervalloDisponibilita::class);
        foreach ($uffici as $ufficio) {
            $intervallo = $intervalliRepo->getIntervallobyOffice($ufficio);
            $prenotazioni = $prenotazioniRepo->getPrenotazioneByUfficio($ufficio);
            $foto = $fotoRepo->getFirstPhotoByOffice($ufficio);
            $fotoUrl = $foto ? "/foto/" . $foto->getId() : null;
            $utente = null;
            $servizio = null;
            if ($prenotazioni !== null) {
                $utente = $prenotazioni->getUtente();
            }
            $data = $prenotazioni->getData();
            $serviziObj = $ufficio->getServiziAggiuntivi();
            $servizi = [];

            foreach ($serviziObj as $s) {
                $servizi[] = $s->getNomeServizio(); // O getDescrizione() o altro campo testuale
            }

            $serviziStringa = implode(', ', $servizi);
            if($data < $oggi){
            $UfficiCompletiPassati[] = [
                'ufficio' => $ufficio,
                'foto' => $fotoUrl,
                'prenotazioni' => $prenotazioni,
                'utente' => $utente,
                'intervallo' => $intervallo,
                'servizio' => $serviziStringa
            ];} else {
                $UfficiCompletiPresenti[] = [
                    'ufficio' => $ufficio,
                    'foto' => $fotoUrl,
                    'prenotazioni' => $prenotazioni,
                    'utente' => $utente,
                    'intervallo' => $intervallo,
                    'servizio' => $serviziStringa
                    ];
            }
        }
        $view = new VUffici();
        $view->searchReservations($UfficiCompletiPassati, $UfficiCompletiPresenti);

        }

    public function addOffice(){
        $view = new VUffici();
        $view->addOfficeV();
    }

}
