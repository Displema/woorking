<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . "/Entity/EIndirizzo.php";

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
$entityManager = getEntityManager();
 $products = $entityManager->getRepository(EIndirizzo::class)->find("8de01155-3571-11f0-b19d-b48c9d833b56");
 echo"". $products->getId() ."";