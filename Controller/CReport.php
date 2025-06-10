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
    public function showForm($id)
    {
  //check if the user is logged
        $this->requireLogin();
        if (!$this->doesLoggedUserHaveRole(Roles::BASIC_USER)) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }
        //take the user from the session
        $user = $this->getUser();
        $view = new VReport();
        $view->showReportForm($id, $user);
    }
    public function showConfirmOfReport(string $id, string $motivo, string $altroTesto)
    {
        $this->requireLogin();

        $userId = $this->auth_manager->getUserId();

        if (!($this->doesLoggedUserHaveRole(Roles::BASIC_USER))) {
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

        if ($motivo === 'Altro') {
            $motivo = $altroTesto;
        }

        $Report= new ESegnalazione();
        $Report->setCommento($motivo);
        $Report->setUfficio($office);
        $Report->setState(ReportStateEnum::class::ACTIVE);
        $this->entity_manager->persist($Report);
        $this->entity_manager->flush();
        $user = $this->getUser();
        $view = new VReport();
        $view->showReportConfirmation($user);
    }

    public function index(): void
    {
        $this->requireLogin();

        $userId = $this->auth_manager->getUserId();

        $user = $this->getUser();
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

    public function show($idoffice)
    {
        $this->requireLogin();
        if (!($this->doesLoggedUserHaveRole(Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }
        $user = $this->getUser();
        $office =$this->entity_manager->getRepository(EUfficio::class)->findoneby(["id" =>$idoffice]);
        $reportsRepo = $this->entity_manager->getRepository(ESegnalazione::class);
        $Report = $reportsRepo->findBy(["user"=>$user,"ufficio"=>$office]);

        $view = new VReport();
        $view->showReport($Report, $office, $user);
    }

    public function Store($id, $reportMotivation, $Text)
    {
   // check if the user is logged
        $this->requireLogin();
        $user = $this->getUser();

        //check the role of the User want to use the page
        if (!($this->doesLoggedUserHaveRole(Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }
        //take the office from DB with entitymanager using repository
        $office=$this->entity_manager->getRepository(EUfficio::class)->find($id);

        //check if the office exist
        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        //check if the user write on the textarea so click "altro"
        if ($reportMotivation === 'Altro') {
            $reportMotivation = $Text?? null;
        }
        //creation of a new report
        $Report= new ESegnalazione();
        //setting of report information
        $Report->setCommento($reportMotivation);
        $Report->setUfficio($office);
        $Report->setUser($user);
        $Report->setState(ReportStateEnum::ACTIVE);
        //save report
        $this->entity_manager->persist($Report);
        $this->entity_manager->flush();

        $view = new VReport();
        $view->showReportConfirmation($user);
    }
}
