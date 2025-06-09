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
        if ($this->auth_manager->isLoggedIn()) {
            try{
            $user = USession::requireUser();

            $userid = $this->auth_manager->getUserId();

            if ($this->auth_manager->admin()->doesUserHaveRole($userid, Roles::LANDLORD)) {
                $view = new VLocatore();
                $view->goHome();
                return;
            }}catch (\TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException $e) {
        // Optional: log error, fallback a null
        $user = null;
    }
        }else {
            $user = null;
        }

        $view = new VHome();
        $view->index($user);
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
