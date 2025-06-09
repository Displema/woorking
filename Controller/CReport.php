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

class CReport extends BaseController
{
    public function showFormReport($id)
    {
        if (!$this->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }
        $user = USession::getUser();
        $view = new VReport();
        $view->showReportForm($id, $user);
    }
    public function showConfirmOfReport($id)
    {
        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }

        $userId = $this->auth_manager->getUserId();

        if (!($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }

        $office=$this->entity_manager->getRepository(EUfficio::class)->find($id);

        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        $commento = $_POST['motivo'] ?? null;

        if ($commento === 'Altro') {
            $commento = $_POST['altroTesto'] ?? null;
        }

        $Report= new ESegnalazione();
        $Report->setCommento($commento);
        $Report->setUfficio($office);
        $Report->setState(ReportStateEnum::class::ACTIVE);
        $this->entity_manager->persist($Report);
        $this->entity_manager->flush();
        $user = USession::getUser();
        $view = new VReport();
        $view->showReportConfirmation($user);
    }

    public function index(): void
    {
        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
        }

        $userId = $this->auth_manager->getUserId();

        $user = USession::getUser();
        $reportsRepo = $this->entity_manager->getRepository(ESegnalazione::class);

        if ($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::ADMIN)) {
            $reports = $reportsRepo->findAll();
            $targetView = "showAdminReports";
        } elseif ($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::BASIC_USER)) {
            $reports = $reportsRepo->findAllByUser($user);
            $targetView = "showUserReports";
        } elseif ($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::LANDLORD)) {
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
}
