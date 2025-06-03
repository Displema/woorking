<?php
namespace Controller;

use Delight\Auth\Auth;
use Doctrine\ORM\EntityManager;
use Model\Enum\StatoUfficioEnum;
use Model\EUfficio;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use View\VAdmin;

class CAdmin
{
    public Auth $auth_manager;
    private EntityManager $entity_manager;

    public function __construct()
    {
        $this->entity_manager = getEntityManager();
        $this->auth_manager = getAuth();
    }

    public function home(): void
    {
        $view = new VAdmin();

        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        $activeOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::Approvato);
        $pendingOffices = $officeRepo->getOfficesByState(StatoUfficioEnum::InAttesa);
        $view->showHome($activeOffices, $pendingOffices);
        //$view->showHome();
    }
}
