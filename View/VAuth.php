<?php
namespace View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class VAuth extends BaseView
{
    public function showLoginForm(): void
    {
        $this->twig->display('User/login/login.html.twig');
    }

    public function showRegisterForm(): void
    {
        $this->twig->display('User/login/login.html.twig');
    }
    public function redirectResetPassword()
    {
        $this->twig->display('errori/infoResetPassword.html.twig');
    }
}
