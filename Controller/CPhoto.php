<?php
namespace Controller;

use DateTime;
use Doctrine\ORM\EntityManager;
use Model\EFoto;
use Model\ESegnalazione;
use Model\EUfficio;
use PHP_CodeSniffer\Reports\Report;
use TechnicalServiceLayer\Foundation\FUfficio;

use TechnicalServiceLayer\Foundation\FEntityManager;
use View\VResource;
use View\VStatus;

class CPhoto
{
    public static function view(string $id)
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
