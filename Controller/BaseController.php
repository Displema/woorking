<?php

namespace Controller;

use Delight\Auth\Auth;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use TechnicalServiceLayer\Roles\Roles;
use View\VRedirect;
use View\VStatus;

abstract class BaseController
{
    protected EntityManagerInterface $entity_manager;
    protected Auth $auth_manager;

    public function __construct()
    {
        $this->entity_manager = getEntityManager();
        $this->auth_manager = getAuth();
    }

    public function requireLogin(): void
    {
        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            exit;
        }
    }

    public function requireRole(string $role): void
    {
        $validRoles = (new \ReflectionClass(Roles::class))->getConstants();

        if (!in_array($role, $validRoles, true)) {
            throw new InvalidArgumentException('Invalid role: ' . $role . 'passed to requireRole()');
        }

        $this->requireLogin();
        $userId = $this->auth_manager->getUserId();

        if (!($this->auth_manager->admin()->doesUserHaveRole($userId, $role))) {
            $view = new VStatus();
            $view->showStatus(403);
            exit;
        }
    }

    public function doesUserHaveRole(string $role): bool
    {
        $validRoles = (new \ReflectionClass(Roles::class))->getConstants();

        if (!in_array($role, $validRoles, true)) {
            throw new InvalidArgumentException('Invalid role: ' . $role . 'passed to requireRole()');
        }

        $this->requireLogin();
        $userId = $this->auth_manager->getUserId();

        if (!($this->auth_manager->admin()->doesUserHaveRole($userId, $role))) {
            return false;
        }

        return true;
    }
}
