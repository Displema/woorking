<?php

namespace View;

use Doctrine\Common\Collections\Collection;
use Model\EUfficio;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class VAdmin extends BaseView
{
    /**
     * @param Collection<int, EUfficio> $activeOffices
     * @param Collection<int, EUfficio> $pendingOffices
     * @param Collection<int, EUfficio> $rejectedOffices
     * @param Collection<int, EUfficio> $hiddenOffices
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function showOfficesIndex(
        Collection $activeOffices,
        Collection $pendingOffices,
        Collection $rejectedOffices,
        Collection $hiddenOffices
    ): void {
        $this->twig->display(
            '/admin/offices/index.html.twig',
            ['activeOffices' => $activeOffices,
                'pendingOffices'=>$pendingOffices,
                'rejectedOffices'=>$rejectedOffices,
                'hiddenOffices'=> $hiddenOffices
            ],
        );
    }

    public function showIndex(): void
    {
        $this->twig->display('/admin/index.html.twig');
    }

    public function showStats(
        $reservationsStats,
        $grossStats,
        $userSignupStats,
        $landlordSignupStats,
    ): void {
        $this->twig->display(
            '/admin/stats/dashboard.html.twig',
            [
            'reservationsStats' => implode(",", $reservationsStats),
            'grossStats' => implode(",", $grossStats),
            'userSignupStats' => implode(",", $userSignupStats),
            'landlordSignupStats' => implode(",", $landlordSignupStats)
            ]
        );
    }

    public function showOfficeDetails(EUfficio $office, string $landlordEmail, int $reservationCount, array $reservationsStats, array $grossStats): void
    {
        $this->twig->display(
            '/admin/offices/office_details.html.twig',
            [
                'office' => $office,
                'email' => $landlordEmail,
                'reservationCount' => $reservationCount,
                'reservationsArray' => implode(",", $reservationsStats),
                'grossArray' => implode(",", $grossStats)
            ],
        );
    }
}
