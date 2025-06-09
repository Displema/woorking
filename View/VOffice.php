<?php

namespace View;

use Model\EUfficio;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class VOffice extends BaseView
{

    public function showOfficeSearch($Result, $date, $fascia1, $user): void
    {
        $this->twig->display('/uffici/uffici.html.twig', ['offices' => $Result,'date' => $date,'fascia' => $fascia1,'user' => $user]);
    }

    public function showOfficeDetails($Result, $date, $fascia, $user): void
    {
        $ufficio = $Result[0];
        $this->twig->display(
            '/DettaglioOffice/DettaglioOffice.html.twig',
            ['ufficio' => $ufficio,
                'date' => $date,
                'fascia' => $fascia,
                'user' => $user,
            ]
        );
    }
    public function showconfirmedpage1($user)
    {

         $this->twig->display('/conferme/confermaprenotazione.html.twig', ['user' => $user]);
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
