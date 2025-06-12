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
    public function showHome($activeOffices, $pendingOffices, $rejectedOffices, $hiddenOffices): void
    {
        $this->twig->display(
            '/admin/home.html.twig',
            ['activeOffices' => $activeOffices, 'pendingOffices'=>$pendingOffices, 'rejectedOffices'=>$rejectedOffices, 'hiddenOffices'=> $hiddenOffices],
        );
    }

    public function showOfficeDetails(EUfficio $office, string $landlordEmail, int $reservationCount, array $reservationsArray): void
    {
        $this->twig->display(
            '/admin/offices/office_details.html.twig',
            [
                'office' => $office,
                'email' => $landlordEmail,
                'reservationCount' => $reservationCount,
                'reservationsArray' => implode(",", $reservationsArray),
                'grossArray' => implode(",", array_map(static function ($item) use ($office) {
                    return $item * $office->getPrezzo();
                }, $reservationsArray))
            ],
        );
    }
}
