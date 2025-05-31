<?php
namespace View;

class VUffici{

    private $loader;

    public function __construct() {
        // Inizializza Twig
        $this->loader = new \Twig\Loader\FilesystemLoader([
            __DIR__ . '/../html',  // vai su di un livello e poi html
        ]);

    }
    public function searchOffice($result) {


        $twig = new \Twig\Environment($this->loader);
        // Render Twig
        echo $twig->render('/gestione_uffici/gestione_uffici.html.twig', ['uffici' => $result]);

    }

    public function searchReservations($resultPassato, $resultPresente) {
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/prenotazioni/gestione_prenotazioni_locatore.html.twig', ['ufficiPassati' => $resultPassato, 'ufficiPresente' => $resultPresente]);
    }

    public function addOfficeV(){
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/aggiunta_ufficio/aggiungi_ufficio.html.twig');
    }



}