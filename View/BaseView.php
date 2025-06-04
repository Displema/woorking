<?php
namespace View;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class BaseView
{
    protected Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../html/');
        $this->twig = new Environment($loader, [
            'debug' => true,
            'cache' => __DIR__ . '/../View/Cache/',
        ]);
        $this->twig->addExtension(new DebugExtension());
    }

}
