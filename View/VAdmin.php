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
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function showHome($activeOffices, $pendingOffices, $rejectedOffices): void
    {
        $this->twig->display(
            '/admin/home.html.twig',
            ['activeOffices' => $activeOffices, 'pendingOffices'=>$pendingOffices, 'rejectedOffices'=>$rejectedOffices],
        );
    }

    public function showOfficeDetails(EUfficio $office, string $landlordEmail, int $reservationCount): void
    {
        $this->twig->display(
            '/admin/offices/office_details.html.twig',
            ['office' => $office,
            'email' => $landlordEmail,
                'reservationCount' => $reservationCount
            ],
        );
    }
}
