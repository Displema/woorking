<?php
namespace Controller;

use Delight\Auth\Auth;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Model\Enum\StatoUfficioEnum;
use Model\EPrenotazione;
use Model\EUfficio;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use TechnicalServiceLayer\Repository\UserRepository;
use TechnicalServiceLayer\Roles\Roles;
use View\VAdmin;
use View\VRedirect;
use View\VStatus;

class CAdmin extends BaseController
{
    public function index(): void
    {
        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }

        $userId = $this->auth_manager->getUserId();

        if (!$this->auth_manager->admin()->doesUserHaveRole($userId, Roles::ADMIN)) {
            $view = new VStatus();
            $view->showStatus(403);
            return;
        }
        $view = new VAdmin();

        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        $activeOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::Approvato);
        $pendingOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::InAttesa);
        $rejectedOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::NonApprovato);
        $view->showHome($activeOffices, $pendingOffices, $rejectedOffices);
    }
}
