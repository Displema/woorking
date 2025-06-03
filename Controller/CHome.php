<?php
namespace Controller;

use Doctrine\ORM\EntityManager;
use TechnicalServiceLayer\Utility\USession;
use View\VHome;
use View\VRedirect;

class CHome
{
    //private EntityManager $entity_manager;

    public function __construct()
    {
        //$this->entity_manager = getEntityManager();
    }

    public function index(): void
    {
        $view = new VHome();
        if (USession::isSetSessionElement('user')) {
            // TODO: add custom navbar for logged users
            $login="isLoggedIn";
        }else{
            $login="NotLoggedIn";
        }
        $view->index($login);
    }

    public function indexRedirect(): void
    {
        $view = new VRedirect();
        $view->redirect('/home');
    }
}
