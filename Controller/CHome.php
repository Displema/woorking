<?php
namespace Controller;

use Doctrine\ORM\EntityManager;
use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;
use TechnicalServiceLayer\Utility\USession;
use View\VHome;
use View\VLocatore;
use View\VRedirect;

class CHome
{
    //private EntityManager $entity_manager;

    public function __construct()
    {

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
            $login="isLoggedIn";
            $user = USession::getSessionElement('user');
        } else {
            $login="NotLoggedIn";
            $user = null;
        }
        $view->index($login, $user);
    }

    public function indexRedirect(): void
    {
        $view = new VRedirect();
        $view->redirect('/home');
    }

    public function showprofile()
    {
        try {
            $user=USession::requireUser();
        } catch (UserNotAuthenticatedException) {
            $view = new VRedirect();
            $view->redirect('/login');
        }

        $view = new VHome();
        $view->profile($user);
    }
}
