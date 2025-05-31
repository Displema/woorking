<?php
namespace View;

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
    public function goHome() {
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('home_locatore/homeLocatore.html.twig', [
            'messaggio' => 'Questa è la pagina principale'
        ]);
    }
}