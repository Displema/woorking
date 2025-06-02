<?php
namespace Controller;


use View\VLocatore;

use TechnicalServiceLayer\Utility\USession;
use View\VHome;
use View\VRedirect;

class CHome
{
    private $twig;

    public function __construct()
    {
        global $twig; // Assumendo che Twig sia inizializzato globalmente
        $this->twig = $twig;
    }

    public function showHome()
    {
        $view = new VLocatore();
        $view->goHome();
    }

    public function index(): void
    {
        $view = new VHome();
        if (USession::isSetSessionElement('user')) {
            // TODO: add custom navbar for logged users
        }
        $view->index();
    }

    public function indexRedirect(): void
    {
        $view = new VRedirect();
        $view->redirect('/home');

    }
}




