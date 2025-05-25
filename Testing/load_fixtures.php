<?php
namespace Testing;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Model\EPagamento;
use Testing\Fixtures\EIndirizzoFixture;
use Testing\Fixtures\EPagamentoFixture;
use Testing\Fixtures\ERecensioneFixture;
use Testing\Fixtures\EPrenotazioneFixture;
use Testing\Fixtures\ERimborsoFixture;
use Testing\Fixtures\EServiziAggiuntiviFixture;
use Testing\Fixtures\EProfiloFixture;
use Testing\Fixtures\ELocatoreFixture;
use Testing\Fixtures\EUfficioFixture;
use Testing\Fixtures\EIntervalliDisponibilitaFixture;
use Testing\Fixtures\ESegnalazioneFixture;

require_once "vendor/autoload.php";
require_once "bootstrap.php";

$loader = new Loader();
//$loader->addFixture(new EProfiloFixture());
//$loader->addFixture(new ELocatoreFixture());
//$loader->addFixture(new EIndirizzoFixture());
//$loader->addFixture(new EIntervalliDisponibilitaFixture());
//$loader->addFixture(new EUfficioFixture());
//$loader->addFixture(new EServiziAggiuntiviFixture());
//$loader->addFixture(new EPagamentoFixture());
//$loader->addFixture(new EPrenotazioneFixture());
//$loader->addFixture(new ERecensioneFixture());
//$loader->addFixture(new ESegnalazioneFixture());
//$loader->addFixture(new ERimborsoFixture());
$loader->loadFromDirectory(__DIR__ . '\Fixtures');

$entityManager = getEntityManager();

$purger = new ORMPurger();
$executor = new ORMExecutor($entityManager, $purger);

// ESEGUIRE SE SI VUOLE PULIRE IL DB
//$executor->purge();
//
$executor->execute($loader->getFixtures());
