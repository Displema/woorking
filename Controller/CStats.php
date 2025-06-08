<?php
namespace Controller;

use Doctrine\ORM\EntityManager;
use Model\EPrenotazione;
use Model\ERecensione;
use Model\EUfficio;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use TechnicalServiceLayer\Repository\ERecensioneRepository;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use TechnicalServiceLayer\Utility\USession;
use View\VHome;

class CStats extends BaseController
{
    public function entrateMensili(): void
    {
        $user = USession::requireUser();
        $repo = $this->entity_manager->getRepository(EPrenotazione::class);

        $data = $repo->getEntrateMensili($user->getId());

        $view = new VHome();
        $view->printJson($data); // Restituisce un JSON tipo: [{"mese": "2024-06", "entrate": 230}]
    }


    public function utilizzoUffici(): void
    {
        $dati = [];
        $user = USession::requireUser();
        $id = $user->getId();
        /** @var EUfficioRepository $uffici */
        $uffici = $this->entity_manager->getRepository(EUfficio::class)->getOfficeByLocatore(['id' => $id]);


        foreach ($uffici as $ufficio) {
        /** @var EPrenotazioneRepository $prenotazioni */
            $prenotazioni = $this->entity_manager->getRepository(EPrenotazione::class)->countByOffice($ufficio);
            $dati[] = [
                'nome' => $ufficio->getTitolo(),
                'numeroPrenotazioni' => $prenotazioni
            ];
        }
        $view = new VHome();
        $view ->printJson($dati);
    }

    public function recensioniCasualiPerLocatore(): void
    {
        $user = USession::requireUser();
        /** @var ERecensioneRepository $repo */
        $repo = $this->entity_manager->getRepository(ERecensione::class);
        $recensioni = $repo->getRandomReviewbyLandlord($user->getId());

        $response = array_map(function ($r) {
            // TODO: si potrebbe fare l'accesso al nome e cognome direttamente nel template per tutte le entità
            return [
                'utente' => $r->getPrenotazione()->getUtente()->getName() . ' ' . $r->getPrenotazione()->getUtente()->getSurname(),
                'commento' => $r->getCommento(),
                'valutazione' => $r->getValutazione()
            ];
        }, $recensioni);

        $view = new VHome();
        $view ->printJson($response);
    }
}
