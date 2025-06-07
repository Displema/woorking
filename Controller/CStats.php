<?php
namespace Controller;

use Doctrine\ORM\EntityManager;
use Model\EPrenotazione;
use Model\EUfficio;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use TechnicalServiceLayer\Utility\USession;
use View\VHome;

class CStats
{

    private EntityManager $entity_manager;

    public function __construct()
    {
        $this->entity_manager = getEntityManager();
        $this->auth_manager = getAuth();
    }


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


        $em = getEntityManager();
        $dati = [];
        $user = USession::requireUser();
        $id = $user->getId();
        /** @var EUfficioRepository $uffici */
        $uffici = $em->getRepository(EUfficio::class)->getOfficeByLocatore(['id' => $id]);


        foreach ($uffici as $ufficio) {
        /** @var EPrenotazioneRepository $prenotazioni */
            $prenotazioni = $em->getRepository(EPrenotazione::class)->countByOffice($ufficio);
            $dati[] = [
                'nome' => $ufficio->getTitolo(),
                'numeroPrenotazioni' => $prenotazioni
            ];
        }
        $view = new VHome();
        $view ->printJson($dati);
    }
}
