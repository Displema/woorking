<?php
namespace Controller;

use Doctrine\ORM\EntityManager;
use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;
use TechnicalServiceLayer\Utility\USession;
use View\BaseView;
use View\VHome;
use View\VLocatore;
use View\VRedirect;

class CLocatore extends BaseController
{
    public function profilo()
    {
        $view = new VLocatore();
        $user = USession::requireUser();


        $view->goProfile($user);
    }
}
