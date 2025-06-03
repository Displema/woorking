<?php

namespace View;

use Doctrine\Common\Collections\Collection;
use Model\EUfficio;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class VAdmin extends BaseView
{
    /**
     * @param Collection<int, EUfficio> $activeOffices
     * @param Collection<int, EUfficio> $pendingOffices
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function showHome($activeOffices, $pendingOffices): void
    {
        $this->twig->display(
            '/admin/home.html.twig',
            ['activeOffices' => $activeOffices, 'pendingOffices'=>$pendingOffices],
        );
    }
}
