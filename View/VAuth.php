<?php
namespace View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class VAuth extends BaseView
{
    public function showLoginForm(): void
    {
        $this->twig->display('login/login.html.twig');
    }

    public function showRegisterForm(): void
    {
        $this->twig->display('login/login.html.twig');
    }

    public function showLoginError(): void
    {
    }
    public function showRegisterError(): void
    {
    }
}
