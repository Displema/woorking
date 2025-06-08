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
use View\VAdmin;
use View\VStatus;

class CAdmin extends BaseController
{
    public function home(): void
    {
        $view = new VAdmin();

        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        $activeOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::Approvato);
        $pendingOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::InAttesa);
        $rejectedOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::NonApprovato);
        $view->showHome($activeOffices, $pendingOffices, $rejectedOffices);
    }


}
