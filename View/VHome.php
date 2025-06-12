<?php
namespace View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class VHome extends BaseView
{

    public function index($user): void
    {
        $this->twig->display('./User/home/homeaccess.html.twig', ['user'=>$user]);
    }

    public function profile($user,$email): void
    {
        $this->twig->display('./User/login/profile.html.twig', ['user'=>$user,'email'=>$email]);
    }
}
