<?php
namespace View;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../bootstrap.php';
use \controller\COffice;


class VReport
{
    private $loader;

// Start Twig

    public function __construct()  {
        $this->loader = new \Twig\Loader\FilesystemLoader([__DIR__ . '/../html',  // on the directory up
            __DIR__       ]);
    }

    public function FormReport($id){
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/segnalazioni/segnalazioni.html.twig',['idufficio' => $id]);
    }
    public function ShowConfirmSendReport(){
        $twig = new \Twig\Environment($this->loader);
        echo $twig->render('/conferme/ConfermaSegnalazione.html.twig');
    }

}
