<?php
namespace Controller;

use Delight\Auth\Auth;
use Doctrine\ORM\EntityManager;
use Model\EProfilo;
use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;
use TechnicalServiceLayer\Repository\UserRepository;
use TechnicalServiceLayer\Roles\Roles;
use TechnicalServiceLayer\Utility\USession;
use View\VHome;
use View\VLocatore;
use View\VRedirect;

class CHome extends BaseController
{
    public function index(): void
    {
        $userId="";
        $user = "";


        if ($this->isLoggedIn()) {
            try {

                $user = USession::getUser();
                $userId = $user->getId();
            }catch(UserNotAuthenticatedException $e){
                print $e->getMessage();
            }
        } else {
            $user = null;
        }


        if ($userId && $this->doesUserHaveRole(Roles::LANDLORD)) {
            $view = new VLocatore();
            $view->index();
            return;
        }

        if ($userId && $this->doesUserHaveRole(Roles::ADMIN)) {
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
    {   //check is the user is logged
        $this->requireLogin();
        //take the user from the session
        $user = USession::getUser();

         //take the email from a method
         $email = UserRepository::getInstance()->getEmailByUserId($user->getUserId())[0]['email'];

        if ($this->doesUserHaveRole(Roles::LANDLORD)) {
            $view = new VLocatore();
            $view->goProfile($user);
            return;
        }

        $view = new VHome();
        $view->profile($user,$email);
    }
}
