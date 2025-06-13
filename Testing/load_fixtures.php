<?php
namespace Testing;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../bootstrap.php";

$loader = new Loader();
$loader->loadFromDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'Fixtures');

$entityManager = getEntityManager();

$purger = new ORMPurger();
$executor = new ORMExecutor($entityManager, $purger);

// ESEGUIRE SE SI VUOLE PULIRE IL DB
while (true) {
    try {
        error_log("Running fixtures...");
        $executor->purge();
        $executor->execute($loader->getFixtures());
        error_log("Finished executing fixtures.");
        exit;
    } catch (\Exception $e) {
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        error_log("Exception while running fixtures... Retrying in 5 seconds");
        sleep(5);
    }
}
