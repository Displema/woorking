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
        $user = USession::requireUser();
        if (USession::isSetSessionElement('user')) {
            // TODO: add custom navbar for logged users
            $login="isLoggedIn";
        }else{
            $login="NotLoggedIn";
        }
        $view->index($login,$user);
    }

    public function indexRedirect(): void
    {
        $view = new VRedirect();
        $view->redirect('/home');
    }

    public function showprofile(){
        $user=USession::requireUser();
        $view = new VHome();
        $view->profile($user);
    }
}
