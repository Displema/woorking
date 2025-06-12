<?php
namespace View;

use Doctrine\Common\Collections\Collection;
use Model\Enum\ReportStateEnum;
use Model\ESegnalazione;
use Model\EUfficio;

class VReport extends BaseView
{
    public function showReport($Report, $office, $user): void
    {
        $this->twig->display('/User/segnalazioni/showreport.html.twig', ['reports'=>$Report,'office'=>$office,'user'=>$user]);
    }
    public function showReportForm($id, $user): void
    {
        $this->twig->display('/User/segnalazioni/segnalazioni.html.twig', ['idoffice' => $id,'user'=>$user]);
    }

    public function showReportConfirmation($user): void
    {
        $this->twig->display('/User/conferme/ConfermaSegnalazione.html.twig', ['user' => $user]);
    }

    public function showUserReports($activeReports, $closedReports): void
    {
        //TODO: paso è roba tua
    }

    /**
     * @param Collection<int, ESegnalazione> $activeReports
     * @param Collection<int, ESegnalazione> $closedReports
     * @return void
     */
    public function showAdminReports($activeReports, $closedReports): void
    {
        $this->twig->display(
            '/admin/reports/reports.html.twig',
            ['activeReports' => $activeReports, 'closedReports' => $closedReports]
        );
    }

    public function showReportDetails(ESegnalazione $report, string $email): void
    {
        $this->twig->display(
            '/admin/reports/details.html.twig',
            [
                'report' => $report,
                'email' => $email
            ]
        );
    }
}
