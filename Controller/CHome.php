<?php
namespace Controller;

use TechnicalServiceLayer\Repository\UserRepository;
use TechnicalServiceLayer\Roles\Roles;
use View\VHome;
use View\VLocatore;
use View\VRedirect;

class CHome extends BaseController
{
    public function index(): void
    {
        if ($this->isLoggedIn()) {
            $user = $this->getUser();
            $userId = $user->getId();
        } else {
            $user = null;
            $userId = null;
        }


        if ($userId && $this->doesLoggedUserHaveRole(Roles::LANDLORD)) {
            $view = new VLocatore();
            $view->index();
            return;
        }

        if ($userId && $this->doesLoggedUserHaveRole(Roles::ADMIN)) {
            $view = new VRedirect();
            $view->redirect('/admin/home');
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
   //check is the user is logged
        $this->requireLogin();
        //take the user from the session
        $user = $this->getUser();

         //take the email
         $email = UserRepository::getInstance()->getEmailByUserId($user->getUserId());

        if ($this->doesLoggedUserHaveRole(Roles::LANDLORD)) {
            $view = new VLocatore();
            $view->goProfile($user);
            return;
        }

        $view = new VHome();
        $view->profile($user, $email);
    }
}
