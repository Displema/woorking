<?php
namespace View;

use Model\EProfilo;

class VLocatore extends BaseView
{

    //rendering home
    public function index(): void
    {
        $this->twig->display('/locatore/homeLocatore/homeLocatore.html.twig', [
            'messaggio' => 'Questa è la pagina principale'
        ]);
    }

    public function goProfile(EProfilo $profilo): void
    {
        $this->twig->display('/locatore/profilo/profilo_locatore.html.twig', ['profilo' => $profilo]);
    }
}
