<?php

namespace View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class VOffice extends BaseView
{

    public function showOfficeSearch($Result, $date, $fascia): void
    {
        $this->twig->display('/uffici/uffici.html.twig', ['offices' => $Result,'date' => $date,'fascia' => $fascia]);
    }

    public function showOfficeDetails($Result, $date, $fascia): void
    {
        $ufficio = $Result[0];
        $this->twig->display(
            '/DettaglioOffice/DettaglioOffice.html.twig',
            ['ufficio' => $ufficio,
                'date' => $date,
                'fascia' => $fascia
            ]
        );
    }
    public function showconfirmedpage1()
    {

        echo $this->twig->display('/conferme/confermaprenotazione.html.twig');
    }

    public function showAllRecension($recensione, $ufficio)
    {
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/recensioni/recensioni.html.twig', ['recensioni' => $recensione,'ufficio' => $ufficio]);
    }
}
