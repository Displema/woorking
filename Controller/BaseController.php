<?php

namespace Controller;

use Delight\Auth\Auth;
use Doctrine\ORM\EntityManagerInterface;

class BaseController
{
    protected EntityManagerInterface $entity_manager;
    protected Auth $auth_manager;

    public function __construct()
    {
        $this->entity_manager = getEntityManager();
        $this->auth = getAuth();
    }
}
