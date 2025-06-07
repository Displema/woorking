<?php
namespace Controller;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Model\Enum\ReportStateEnum;
use Model\EProfilo;
use Model\ESegnalazione;
use Model\EUfficio;
use PHP_CodeSniffer\Reports\Report;

use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use TechnicalServiceLayer\Roles\Roles;
use TechnicalServiceLayer\Utility\USession;
use View\VRedirect;
use View\VReport;
use View\VStatus;

class CReport
{
    private EntityManager $entity_manager;

    public function __construct()
    {
        $this->entity_manager = getEntityManager();
    }

    public function showFormReport($id)
    {
        $view = new VReport();
        if (USession::isSetSessionElement('user')) {
            $utente = USession::requireUser();
            $login="isLoggedIn";
            $user = $this->entity_manager->getRepository(EProfilo::class)->find($utente->getId());
        }
        $view->showReportForm($id, $user, $login);
    }
    public function showConfirmOfReport($id)
    {
        $commento = $_POST['motivo'] ?? null;

        if ($commento == 'Altro') {
            $commento = $_POST['altroTesto'] ?? null;
        }
        if (USession::isSetSessionElement('user')) {
            $utente = USession::requireUser();
            $login="isLoggedIn";
            $user = $this->entity_manager->getRepository(EProfilo::class)->find($utente->getId());
        }

        $ufficio=$this->entity_manager->getRepository(EUfficio::class)->find($id);

        $Report= new ESegnalazione();
        $Report->setCommento($commento);
        $Report->setUfficio($ufficio);
        $Report->setState(ReportStateEnum::class::ACTIVE);
        $this->entity_manager->persist($Report);
        $this->entity_manager->flush();

        $view = new VReport();
        $view->showReportConfirmation($user, $login);
    }

    public function index(): void
    {
        $auth = getAuth();
        if (!$auth->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
        }

        $userId = $auth->getUserId();

        $user = USession::requireUser();
        $reportsRepo = $this->entity_manager->getRepository(ESegnalazione::class);

        if ($auth->admin()->doesUserHaveRole($userId, Roles::ADMIN)) {
            $reports = $reportsRepo->findAll();
            $targetView = "showAdminReports";
        } elseif ($auth->admin()->doesUserHaveRole($userId, Roles::BASIC_USER)) {
            $reports = $reportsRepo->findAllByUser($user);
            $targetView = "showUserReports";
        } elseif ($auth->admin()->doesUserHaveRole($userId, Roles::LANDLORD)) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        } else {
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }

        $activeReports = array_values(array_filter($reports, function ($report) {
            return $report->getState() === ReportStateEnum::ACTIVE;
        }));
        $closedReports = array_values(array_filter($reports, function ($report) {
            return $report->getState() === ReportStateEnum::SOLVED;
        }));

        $view = new VReport();
        $view->$targetView($activeReports, $closedReports);
    }

    public function show(string $id): void
    {
        $auth = getAuth();
        if (!$auth->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }

        $userId = $auth->getUserId();
        $user = USession::requireUser();
        $reportsRepo = $this->entity_manager->getRepository(ESegnalazione::class);
        $report = $reportsRepo->find($id);

        if (!$report) {
            $view = new VRedirect();
            $view->redirect('/reports');
            return;
        }

        if (!($report->getState() === ReportStateEnum::ACTIVE)) {
            $view = new VRedirect();
            $view->redirect('/reports');
            return;
        }

        // TODO: show report details page
    }
}
