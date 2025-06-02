<?php
namespace View;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../bootstrap.php';
use \Controller\COffice;


class Vmostrauffici
{
private $loader;

// Start Twig

public function __construct()  {
    $this->loader = new \Twig\Loader\FilesystemLoader([__DIR__ . '/../html',  // on the directory up
        __DIR__       ]);
}

public function startsearch(){
    $twig = new \Twig\Environment($this->loader);
    echo $twig->render('/home/homeaccess.html.twig');
}
// Read params GET
public function showuffici($Result,$date,$fascia){
     $twig = new \Twig\Environment($this->loader);
    echo $twig->render('/uffici/uffici.html.twig', ['offices' => $Result,'date' => $date,'fascia' => $fascia]);
}

public function showOfficedetails( $Result,$date,$fascia){
    $twig = new \Twig\Environment($this->loader);
    $ufficio = $Result[0];
    echo $twig->render('/DettaglioOffice/DettaglioOffice.html.twig', ['ufficio' => $ufficio,'date' => $date,'fascia' => $fascia]);
}
public function showconfirmedpage1(){
    $twig = new \Twig\Environment($this->loader);
    echo $twig->render('/conferme/confermaprenotazione.html.twig');
}

public function showAllRecension($recensione,$ufficio){
    $twig = new \Twig\Environment($this->loader);
    echo $twig->render('/recensioni/recensioni.html.twig', ['recensioni' => $recensione,'ufficio' => $ufficio]);
}



}
