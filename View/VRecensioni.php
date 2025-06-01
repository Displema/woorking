<?php
namespace View;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../bootstrap.php';
use \controller\COffice;


class VRecensioni
{
    private $loader;

// Start Twig

    public function __construct()  {
        $this->loader = new \Twig\Loader\FilesystemLoader([__DIR__ . '/../html',  // on the directory up
            __DIR__       ]);
    }

    public function showAllRecension($review,$office){
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/recensioni/recensioni.html.twig', ['reviews' => $review,'office' => $office]);
    }
    public function formreview($idreservation){
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/recensioni/lasciaunarecensione.html.twig',['reservation' => $idreservation]);
    }

    public function confirmreview(){
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/conferme/confermarecensione.html.twig');
    }
}