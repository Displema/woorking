<?php
namespace Controller;

use Doctrine\ORM\EntityManager;
use TechnicalServiceLayer\Utility\USession;
use View\VHome;

class CHome
{
    private EntityManager $entity_manager;

    public function __construct()
    {
        $this->entity_manager = getEntityManager();
    }

    public function index()
    {
        if (USession::isSetSessionElement('user')) {
            echo USession::getSessionElement('user');
        }

        $view = new VHome();
        $view->index();
    }
}
