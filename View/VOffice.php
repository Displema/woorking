<?php

namespace View;

use Model\EUfficio;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class VOffice extends BaseView
{

    public function showOfficeSearch($offices, $date, $fascia1, $user): void
    {
        $this->twig->display('/User/uffici/uffici.html.twig', ['offices' => $offices,'date' => $date,'fascia' => $fascia1,'user' => $user]);
    }

    public function showOfficeDetails($office, $date, $slot, $user): void
    {

        $this->twig->display(
            '/User/DettaglioOffice/DettaglioOffice.html.twig',
            ['office' => $office,
                'date' => $date,
                'slot' => $slot,
                'user' => $user,
            ]
        );
    }
    public function showconfirmedpage1($user)
    {

         $this->twig->display('/User/conferme/confermaprenotazione.html.twig', ['user' => $user]);
    }

    public function showAllReviews($recensione, $ufficio,)
    {

        $this->twig->display('/recensioni/recensioni.html.twig', ['recensioni' => $recensione,'ufficio' => $ufficio,]);
    }

    public function searchOfficeLocatore($approvati, $nonApprovati)
    {

        // Render Twig
        $this->twig->display('/landlord/gestioneUffici/gestione_uffici.html.twig', ['ufficiA' => $approvati, 'nonApprovati' => $nonApprovati]);
    }



    public function addOfficeV()
    {

        $this->twig->display('/landlord/aggiuntaUfficio/aggiungi_ufficio.html.twig');
    }

    public function showPendingAdmin(EUfficio $office): void
    {
        $this->twig->display('/admin/offices/pending_details_page.html.twig', ['office' => $office]);
    }

    public function showRejectedAdmin(EUfficio $office): void
    {
        $this->twig->display('/admin/offices/rejected_details.html.twig', ['office' => $office]);
    }

    public function showRejectedLandlord(EUfficio $office): void
    {
        $this->twig->display('/landlord/gestioneUffici/uffici_rifiutati.html.twig', ['office' => $office]);
    }
}
