<?php
namespace Controller;

use Exception;
use Model\EFoto;
use Ramsey\Uuid\Uuid;
use View\VResource;
use View\VStatus;

class CPhoto
{
    public function view(string $id): void
    {

        $em = getEntityManager()->getRepository(EFoto::class);
        $foto = $em->find($id);
        if (!$foto) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }
        $viewResource = new VResource();
        $viewResource->showPhoto($foto);
    }
}
