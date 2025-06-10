<?php
namespace View;

class VRedirect extends BaseView
{
    public function redirect(string $path): void
    {
        header('Location: ' . $path);
    }



}
