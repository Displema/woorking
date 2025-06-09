<?php
namespace View;

use Model\EFoto;

class VResource
{
    public function __construct()
    {
    }

    public function showPhoto(EFoto $foto): void
    {
        header("Content-Type: " . $foto->getMimeType());
        header("Content-Length: " . $foto->getSize());

        if (is_resource($foto->getContent())) {
            $content = stream_get_contents($foto->getContent());
        }

        if (empty($content)) {
            echo "404 not found";
        }

        echo $content;
    }

    public function printJson(mixed $data): void
    {
        header('Content-Type: application/json');
        echo $data;
    }

    public function printText(string $data): void
    {
        header('Content-Type: application/html');
        echo $data;
    }
}
