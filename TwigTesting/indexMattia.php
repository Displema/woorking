<?php
namespace Twig;

use Model\EFoto;
use Model\EIntervalloDisponibilita;
use Model\EServiziAggiuntivi;
use Model\EUfficio;


require_once 'C:\Users\Lenovo\Desktop\woorking\bootstrap.php';
require_once 'C:\Users\Lenovo\Desktop\woorking\vendor/autoload.php';

$entity_manager = getEntityManager();

$uffici = $entity_manager->getRepository(EUfficio::class)->findAll();
$UfficiCompleti = [];
$fotoRepo = $entity_manager->getRepository(EFoto::class);
$serviziRepo = $entity_manager->getRepository(EServiziAggiuntivi::class);
$intervalliRepo = $entity_manager->getRepository(EIntervalloDisponibilita::class);

foreach ($uffici as $ufficio) {

    $foto = $fotoRepo->getFirstPhotoByOffice($ufficio);
    $fotoUrl = $foto ? 'TwigTesting/serve_foto.php?id=' . $foto->getId() : null;
    $servizi = $serviziRepo->getServiziAggiuntivibyOffice($ufficio);
    $intervallo = $intervalliRepo->getIntervallobyOffice($ufficio);


    $UfficiCompleti[] = [
        'ufficio' => $ufficio,
        'foto' => $fotoUrl,
        'servizi' => $servizi,
        'intervallo' => $intervallo
    ];

}
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/Templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

echo $twig->render('gestione_uffici.html.twig', [

    'uffici' => $UfficiCompleti,
]);