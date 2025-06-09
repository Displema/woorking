<?php
namespace View;

use Doctrine\Common\Collections\Collection;
use Model\Enum\ReportStateEnum;
use Model\ESegnalazione;

class VReport extends BaseView
{
    public function showReportForm($id, $user): void
    {
        $this->twig->display('/segnalazioni/segnalazioni.html.twig', ['idufficio' => $id,'user'=>$user]);
    }

    public function showReportConfirmation($user): void
    {
        $this->twig->display('/conferme/ConfermaSegnalazione.html.twig', ['user' => $user]);
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
        foreach ($activeReports as $report) {
            error_log($report->getId());
        }
        foreach ($closedReports as $report) {
            error_log($report->getId());
        }

        $this->twig->display(
            '/admin/reports/reports.html.twig',
            ['activeReports' => $activeReports, 'closedReports' => $closedReports]
        );
    }
}
