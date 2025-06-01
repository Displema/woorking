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
        $view = new VHome();
        if (USession::isSetSessionElement('user')) {

        }


        $view->index();
    }
}
