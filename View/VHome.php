<?php
namespace View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class VHome extends BaseView
{

    public function index(): void
    {
        $this->twig->display('./home/homeaccess.html.twig');
    }
}
