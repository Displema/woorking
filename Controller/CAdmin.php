<?php
namespace Controller;

use Delight\Auth\Auth;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Model\Enum\StatoUfficioEnum;
use Model\EUfficio;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use TechnicalServiceLayer\Repository\UserRepository;
use View\VAdmin;
use View\VStatus;

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
    }

    public function showOfficeDetails(string $id): void
    {
        $view = new VAdmin();
        try {
            $office = $this->entity_manager->find(EUfficio::class, $id);
        } catch (ValueNotConvertible) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
            $view = new VStatus();
            $view->showStatus(500);
            return;
        }

        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        $view = new VAdmin();
        $userRepo = UserRepository::getInstance();
        $email = $userRepo->getEmailByUserId($office->getLocatore()->getUserId())[0]['email'];
        $view->showOfficeDetails($office, $email);
    }
}
