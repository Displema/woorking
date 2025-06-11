<?php
namespace Controller;

use Model\EFoto;
use View\VResource;
use View\VStatus;

class CResource extends BaseController
{
    private function readFileFromFolder(string $folder, string $filename): mixed
    {
        $folders = ['html' . DIRECTORY_SEPARATOR .  'admin' . DIRECTORY_SEPARATOR . $folder,
            'html' . DIRECTORY_SEPARATOR .  'landlord' . DIRECTORY_SEPARATOR . $folder,
            'html' . DIRECTORY_SEPARATOR .  'user' . DIRECTORY_SEPARATOR . $folder];

        // Input file sanification
        $parsedFilename = basename($filename); // Removes paths

        if (!preg_match("/^[a-zA-Z0-9_\-]+\.$folder$/", $parsedFilename)) {
            error_log($parsedFilename);
            return null;
        }
        // Look for file inside the authorized folders
        foreach ($folders as $x) {
            $path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $x . DIRECTORY_SEPARATOR . $parsedFilename;

            if (file_exists($path) && is_file($path)) {
                return file_get_contents($path);
            }
        }

        // If file was not found return null
        return null;
    }

    public function serveImg(string $id): void
    {
        $repository = $this->entity_manager->getRepository(EFoto::class);
        $foto = $repository->find($id);
        if (!$foto) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }
        $viewResource = new VResource();
        $viewResource->showPhoto($foto);
    }

    public function serveCss(string $id): void
    {
        $content = $this->readFileFromFolder("css", $id);
        if (!$content) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        $view = new VResource();
        $view->serve($content, "text/css");
    }

    public function serveJs(string $id): void
    {
        $content = $this->readFileFromFolder("js", $id);
        if (!$content) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        $view = new VResource();
        $view->serve($content, "text/javascript");
    }
}
