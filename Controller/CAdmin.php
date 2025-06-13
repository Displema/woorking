<?php
namespace Controller;

use Model\EPrenotazione;
use Model\EProfilo;
use Model\EUfficio;
use TechnicalServiceLayer\Repository\ELocatoreRepository;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use TechnicalServiceLayer\Repository\EProfiloRepository;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use TechnicalServiceLayer\Roles\Roles;
use View\VAdmin;

class CAdmin extends BaseController
{
    public function index(): void
    {
        $this->requireRole(Roles::ADMIN);
        $view = new VAdmin();
        $view->showIndex();
    }

    public function statsIndex(): void
    {
        $this->requireRole(Roles::ADMIN);

        /** @var EPrenotazioneRepository $reservationsRepo */
        $reservationsRepo = $this->entity_manager->getRepository(EPrenotazione::class);
        $reservationsStats = $reservationsRepo->getReservationsByMonths();

        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        $meanPrice = $officeRepo->getMeanPrice();
        $grossStats = $grossStats = array_map(static function ($item) use ($meanPrice) {
            return $item * $meanPrice;
        }, $reservationsStats);

        /** @var EProfiloRepository $usersRepo */
        $usersRepo = $this->entity_manager->getRepository(EProfilo::class);
        $usersStats = $usersRepo->getSignupByMonths();

        /** @var ELocatoreRepository $usersRepo */
        $landlordRepo = $this->entity_manager->getRepository(EProfilo::class);
        $landlordStats = $landlordRepo->getSignupByMonths();

        $view = new VAdmin();
        $view->showStats($reservationsStats, $grossStats, $usersStats, $landlordStats);
    }
}
