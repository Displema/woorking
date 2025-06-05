<?php
namespace Controller;



use Doctrine\ORM\EntityManager;
use Model\EPrenotazione;
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
}