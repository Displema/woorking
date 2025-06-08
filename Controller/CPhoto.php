<?php
namespace Controller;

use Model\EFoto;
use View\VResource;
use View\VStatus;

class CPhoto extends BaseController
{
    public function view(string $id): void
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
}
