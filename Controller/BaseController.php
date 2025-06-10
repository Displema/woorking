<?php

namespace Controller;

use Delight\Auth\Auth;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Model\ELocatore;
use Model\EProfilo;
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
//        $this->auth_manager->admin()->logInAsUserByEmail("amessina@mazza.it");
//        $userId = $this->auth_manager->getUserId();
//        $user = $this->entity_manager->getRepository(EProfilo::class)->findOneBy(
//            ["user_id" => $userId]
//        );
//        $_SESSION['user'] = $user;

        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            exit;
        }
    }

    public function requireRole(int $role): void
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

    public function requireRoles(array $roles): void
    {
        $validRoles = (new \ReflectionClass(Roles::class))->getConstants();

        foreach ($roles as $role) {
            if (!in_array($role, $validRoles, true)) {
                throw new InvalidArgumentException('Invalid role passed to requireRoles()');
            }
        }

        $this->requireLogin();
        $userId = $this->auth_manager->getUserId();

        $found = false;
        foreach ($roles as $role) {
            if ($this->auth_manager->admin()->doesUserHaveRole($userId, $role)) {
                $found = true;
            }
        }
        if (!$found) {
            $view = new VStatus();
            $view->showStatus(403);
            exit;
        }
    }

    public function doesLoggedUserHaveRole(int $role): bool
    {
        $validRoles = (new \ReflectionClass(Roles::class))->getConstants();

        if (!in_array($role, $validRoles, true)) {
            throw new InvalidArgumentException('Invalid role: ' . $role . ' passed to requireRole()');
        }

        $this->requireLogin();
        $userId = $this->auth_manager->getUserId();

        if (!($this->auth_manager->admin()->doesUserHaveRole($userId, $role))) {
            return false;
        }

        return true;
    }

    public function isLoggedIn(): bool
    {
        return $this->auth_manager->isLoggedIn();
    }

    public function getUserId(): int
    {
        return $this->auth_manager->getUserId();
    }
}
