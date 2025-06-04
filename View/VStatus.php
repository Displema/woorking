<?php

namespace View;

class VStatus extends BaseView
{
    public function showStatus(int $status_code): void
    {
        http_response_code($status_code);
        $this->twig->display(
            '/errori/40x.html.twig',
            ['statusCode' => $status_code]
        );
    }
}
