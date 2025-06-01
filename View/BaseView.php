<?php
namespace View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BaseView
{
    protected Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../html/');
        $this->twig = new Environment($loader);
    }

}
