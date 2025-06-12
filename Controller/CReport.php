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
use TechnicalServiceLayer\Repository\ESegnalazioneRepository;
use TechnicalServiceLayer\Repository\UserRepository;
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


    public function index(): void
    {
        $this->requireLogin();

        $user = $this->getUser();
        /** @var ESegnalazioneRepository $reportsRepo */
        $reportsRepo = $this->entity_manager->getRepository(ESegnalazione::class);

        if ($this->doesLoggedUserHaveRole(Roles::ADMIN)) {
            $reports = $reportsRepo->findAll();
            $targetView = "showAdminReports";
        } elseif ($this->doesLoggedUserHaveRole(Roles::BASIC_USER)) {
            $reports = $reportsRepo->findAllByUser($user);
            $targetView = "showUserReports";
        } elseif ($this->doesLoggedUserHaveRole(Roles::LANDLORD)) {
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
        $Report
            ->setCommento($reportMotivation)
            ->setUfficio($office)
            ->setUser($user)
            ->setState(ReportStateEnum::ACTIVE)
            ->setCreatedAt(new DateTime());

        //save report
        $this->entity_manager->persist($Report);
        $this->entity_manager->flush();

        $view = new VReport();
        $view->showReportConfirmation($user);
    }

    public function handleReportDetails(string $id): void
    {
        $this->requireRole(Roles::ADMIN);

        $report = $this->entity_manager->getRepository(ESegnalazione::class)->find($id);

        if (!$report) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        $email = UserRepository::getInstance()->getEmailByUserId($report->getUser()->getUserId());
        $view = new VReport();
        $view->showReportDetails($report, $email);
    }

    public function submitReportResolution(string $id, string $resolutionMessage): void
    {
        $this->requireRole(Roles::ADMIN);

        $report = $this->entity_manager->getRepository(ESegnalazione::class)->find($id);

        error_log($id);
        error_log($resolutionMessage);

        if (!$report) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        if (!$resolutionMessage || $report->getState() === ReportStateEnum::SOLVED) {
            $view = new VStatus();
            $view->showStatus(400);
            return;
        }

        $this->entity_manager->beginTransaction();


        try {
            $report->setState(ReportStateEnum::SOLVED)
                ->setUpdatedAt(new DateTime())
                ->setCommentoAdmin($resolutionMessage);
            $this->entity_manager->persist($report);
            $this->entity_manager->flush();
            $this->entity_manager->commit();
        } catch (\Exception $e) {
            if ($this->entity_manager->getConnection()->isTransactionActive()) {
                $this->entity_manager->rollback();
            }
            $view = new VStatus();
            $view->showStatus(500);
            return;
        }

        $view = new VRedirect();
        $view->redirect("/admin/reports/$id");
    }
}
