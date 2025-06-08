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

        $viewUser = new VHome();
        $viewLandlord = new VLocatore();

        if (USession::isSetSessionElement('user')) {
            $login="isLoggedIn";
            $user = USession::getSessionElement('user');


            $userid = $this->auth_manager->getUserId();

            if ($this->auth_manager->admin()->doesUserHaveRole($userid, Roles::LANDLORD)) {
                $viewLandlord->goHome();
                return;
            }
        } else {
            $login="NotLoggedIn";
            $user = null;
        }


        $viewUser->index($login, $user);
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
