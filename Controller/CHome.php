<?php
namespace Controller;

use Delight\Auth\Auth;
use Doctrine\ORM\EntityManager;
use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;
use TechnicalServiceLayer\Roles\Roles;
use TechnicalServiceLayer\Utility\USession;
use View\VHome;
use View\VLocatore;
use View\VRedirect;

class CHome extends BaseController
{
    public function index(): void
    {

        if ($this->isLoggedIn()) {
            $user = USession::getUser();
            $userId = $user->getId();
        } else {
            $user = null;
            $userId = null;
        }


        if ($userId && $this->doesUserHaveRole(Roles::LANDLORD)) {
            $view = new VLocatore();
            $view->index();
            return;
        }

        $view = new VHome();
        $view->index($user);
    }

    public function redirect(): void
    {
        $view = new VRedirect();
        $view->redirect('/home');
    }

    public function profile(): void
    {
        $this->requireLogin();

        $user = USession::getUser();

        if ($this->doesUserHaveRole(Roles::LANDLORD)) {
            $view = new VLocatore();
            $view->goProfile($user);
            return;
        }

        $view = new VHome();
        $view->profile($user);
    }
}
