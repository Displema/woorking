<?php
namespace View;

use Model\EProfilo;

class VLocatore extends BaseView
{

    //rendering home
    public function index(): void
    {
        $this->twig->display('/landlord/homeLocatore/homeLocatore.html.twig', [
            'messaggio' => 'Questa è la pagina principale'
        ]);
    }

    public function goProfile(EProfilo $profilo): void
    {
        $this->twig->display('/landlord/profilo/profilo_locatore.html.twig', ['profilo' => $profilo]);
    }
}
