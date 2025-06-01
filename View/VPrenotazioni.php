<?php
namespace View;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../bootstrap.php';
use \controller\COffice;


class VPrenotazioni
{
    private $loader;

// Start Twig

    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader([__DIR__ . '/../html',  // on the directory up
            __DIR__]);
    }

    public function showPrenotation($reservation,$oldreservation){
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/Prenotazioni/Prenotazione.html.twig', ['reservations' => $reservation,'oldreservations'=>$oldreservation]);
    }

    public function showReservationDetails($reservation){
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('Prenotazioni/VisualizzaPrenotazioni.html.twig', ['reservations' => $reservation]);
    }

    public function showplacenotavaible(){
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/conferme/Postinondisponibili.html.twig');
    }
}