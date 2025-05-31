<?php
namespace Controller;

use View\VLocatore;

class CHome {
    private $twig;

    public function __construct() {
        global $twig; // Assumendo che Twig sia inizializzato globalmente
        $this->twig = $twig;
    }

    public function showHome() {
        $view = new VLocatore();
        $view->goHome();


    }
}
