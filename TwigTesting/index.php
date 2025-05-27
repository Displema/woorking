<?php
namespace Twig;

use Model\ESegnalazione;


require_once 'C:\Users\displema\PhpstormProjects\Woorking\bootstrap.php';
require_once 'C:\Users\displema\PhpstormProjects\Woorking\vendor/autoload.php';

$entity_manager = getEntityManager();

$segnalazioni = $entity_manager->getRepository(ESegnalazione::class)->findAll();

