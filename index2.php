<?php
namespace Twig;

use Model\EFoto;
use Model\ESegnalazione;
use Model\EUfficio;
use TechnicalServiceLayer\Repository\EIntervalloDisponibilitaRepository;

require_once 'vendor/autoload.php';
require_once 'bootstrap.php';

$loader = new \Twig\Loader\FilesystemLoader('./TwigTesting/Templates');
$twig = new \Twig\Environment($loader, [
    'cache' => '/cache',
]);



$entity_manager = getEntityManager();
$ufficio = $entity_manager->find(EUfficio::class, "16947970-e548-4834-8794-6f8ce5a7dd70");

/** @var EIntervalloDisponibilitaRepository $repo */
$repo = $entity_manager->getRepository(EFoto::class);

$foto = $repo->getAllPhotosByOffice($ufficio);

foreach ($foto as $x) {
}


//echo $twig->render('segnalazioni.html.twig', ['segnalazioni' => array($segnalazioni)]);
