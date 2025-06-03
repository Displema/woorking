<?php
namespace View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class VHome extends BaseView
{

    public function index($login,$user): void
    {
        $this->twig->display('./home/homeaccess.html.twig', ['isloggedin' => $login,'user'=>$user]);
    }

    public function profile($user): void
    {
        $this->twig->display('./login/profile.html.twig', ['user'=>$user]);
    }
}
