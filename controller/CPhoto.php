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

class CPhoto
{
    public static function view(string $id)
    {
        $em = getEntityManager()->getRepository(EFoto::class);
        $foto = $em->find($id);
        $viewResource = new VResource();
        $viewResource->showPhoto($foto);
    }
}
