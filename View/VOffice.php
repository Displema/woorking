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

    public function searchOfficeLocatore($result)
    {

        // Render Twig
        $this->twig->display('/locatore/gestioneUffici/gestione_uffici.html.twig', ['uffici' => $result]);
    }

    public function searchReservations($resultPassato, $resultPresente)
    {

        $this->twig->display('/locatore/prenotazioni/gestione_prenotazioni_locatore.html.twig', ['ufficiPassati' => $resultPassato, 'ufficiPresente' => $resultPresente]);
    }

    public function addOfficeV()
    {

        $this->twig->display('/locatore/aggiuntaUfficio/aggiungi_ufficio.html.twig');
    }

    public function showPendingLandlord($office): void
    {
        //TODO: vista locatore ufficio in attesa
    }

    public function showPendingAdmin(EUfficio $office): void
    {
        $this->twig->display('/admin/offices/pending_details_page.html.twig', ['office' => $office]);
    }
}
