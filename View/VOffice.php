<?php

namespace View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class VOffice extends BaseView
{

    public function showOfficeSearch($Result, $date, $fascia,$user,$login): void
    {
        $this->twig->display('/uffici/uffici.html.twig', ['offices' => $Result,'date' => $date,'fascia' => $fascia,'user' => $user,'isloggedin' => $login]);
    }

    public function showOfficeDetails($Result, $date, $fascia,$user,$login): void
    {
        $ufficio = $Result[0];
        $this->twig->display(
            '/DettaglioOffice/DettaglioOffice.html.twig',
            ['ufficio' => $ufficio,
                'date' => $date,
                'fascia' => $fascia,
                'user' => $user,
                'isloggedin' => $login
            ]
        );
    }
    public function showconfirmedpage1($user,$login)
    {

         $this->twig->display('/conferme/confermaprenotazione.html.twig',['user' => $user,'isloggedin' => $login]);
    }

    public function showAllReviews($recensione, $ufficio,)
    {

        $this->twig->display('/recensioni/recensioni.html.twig', ['recensioni' => $recensione,'ufficio' => $ufficio,]);
    }

    public function searchOfficeLocatore($result) {

        // Render Twig
        $this->twig->display('/locatore/gestioneUffici/gestione_uffici.html.twig', ['uffici' => $result]);
    }

    public function searchReservations($resultPassato, $resultPresente) {

        $this->twig->display('/locatore/prenotazioni/gestione_prenotazioni_locatore.html.twig', ['ufficiPassati' => $resultPassato, 'ufficiPresente' => $resultPresente]);
    }

    public function addOfficeV(){

        $this->twig->display('/locatore/aggiuntaUfficio/aggiungi_ufficio.html.twig');
    }
}
