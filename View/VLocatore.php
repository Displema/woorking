<?php
namespace View;

use Model\EProfilo;

class VLocatore
{

    private $loader;

    public function __construct()
    {
        // Inizializza Twig
        $this->loader = new \Twig\Loader\FilesystemLoader([
            __DIR__ . '/../html',  // vai su di un livello e poi html
        ]);

    }

    //rendering home
    public function index() {
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/locatore/homeLocatore/homeLocatore.html.twig', [
            'messaggio' => 'Questa è la pagina principale'
        ]);
    }

    public function goProfile(EProfilo $profilo) {
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/locatore/profilo/profilo_locatore.html.twig', ['profilo' => $profilo]);
    }
}