<?php
namespace Twig;

use Model\EFoto;
use Model\EIntervalloDisponibilita;
use Model\EServiziAggiuntivi;
use Model\EUfficio;


require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
/*
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
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '\html');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

echo $twig->render('/gestione_uffici/gestione_uffici.html.twig', [

    'uffici' => $UfficiCompleti,
]);
*/
//loading route file
$router = require __DIR__ . '/Routes/web.php';

//execution of dispatch
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

