<?php
namespace Controller;

use Model\EPrenotazione;
use Model\ERecensione;
use Model\EUfficio;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use TechnicalServiceLayer\Repository\ERecensioneRepository;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use TechnicalServiceLayer\Roles\Roles;
use TechnicalServiceLayer\Utility\USession;
use View\VHome;
use View\VResource;

class CStats extends BaseController
{
    public function entrateMensili(): void
    {
        $this->requireRole(Roles::LANDLORD);
        $user = $this->getUser();
        /** @var EPrenotazioneRepository $repo */
        $repo = $this->entity_manager->getRepository(EPrenotazione::class);

        $data = $repo->getEntrateMensili($user->getId());

        $view = new VResource();
        $view->printJson($data); // Restituisce un JSON tipo: [{"mese": "2024-06", "entrate": 230}]
    }


    public function utilizzoUffici(): void
    {
        $this->requireRole(Roles::LANDLORD);
        $dati = [];
        $user = $this->getUser();
        $id = $user->getId();
        /** @var EUfficioRepository $uffici */
        $uffici = $this->entity_manager->getRepository(EUfficio::class)->getAllOfficeByLocatore(['id' => $id]);


        foreach ($uffici as $ufficio) {
            $prenotazioni = $this->entity_manager->getRepository(EPrenotazione::class)->countByOffice($ufficio);
            $dati[] = [
                'nome' => $ufficio->getTitolo(),
                'numeroPrenotazioni' => $prenotazioni
            ];
        }
        $view = new VResource();
        $view ->printJson($dati);
    }

    public function recensioniCasualiPerLocatore(): void
    {
        $this->requireRole(Roles::LANDLORD);
        $user = $this->getUser();
        /** @var ERecensioneRepository $repo */
        $repo = $this->entity_manager->getRepository(ERecensione::class);
        $recensioni = $repo->getRandomReviewbyLandlord($user->getId());

        $response = null;
        if ($recensioni != null) {
            $response =  [
                'utente' => $recensioni->getPrenotazione()->getUtente()->getName() . ' ' . $recensioni->getPrenotazione()->getUtente()->getSurname(),
                'commento' => $recensioni->getCommento(),
                'valutazione' => $recensioni->getValutazione()
            ];
        }

        $view = new VResource();
        $view ->printJson($response);
    }

    public function recensioniPerUfficio(): void
    {
        $this->requireRole(Roles::LANDLORD);

        $user = $this->getUser();


        $nomeUfficio = $_GET['ufficio'] ?? null;


        if (!$nomeUfficio) {
            http_response_code(400);
            echo json_encode(['error' => 'Parametro "ufficio" mancante']);
            return;
        }

        /** @var ERecensioneRepository $repo */
        $repo = $this->entity_manager->getRepository(ERecensione::class);


        $recensioni = $repo->getReviewByOfficeNameAndLandlord($nomeUfficio, $user->getId());

        $response = array_map(function ($r) {
            return [
                'utente' => $r->getPrenotazione()->getUtente()->getName() . ' ' . $r->getPrenotazione()->getUtente()->getSurname(),
                'commento' => $r->getCommento(),
                'valutazione' => $r->getValutazione(),
            ];
        }, $recensioni);

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
