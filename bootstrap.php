<?php
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

//require_once "./TechnicalServiceLayer/Foundation/FEntityManager.php";

use Doctrine\DBAL\Types\Type;
use Ramsey\Uuid\Doctrine\UuidType;

if (!Type::hasType('uuid')) {
    Type::addType('uuid', UuidType::class);
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dotenv->required('DB_NAME')->notEmpty();
$dotenv->required('DB_USER')->notEmpty();
$dotenv->required('DB_PASSWORD');
$dotenv->required('DB_HOST')->notEmpty();
$dotenv->required('DB_PORT')->notEmpty();
$dotenv->required('DB_DRIVER')->allowedValues(['pdo_mysql']);
function getEntityManager(): EntityManager
{
    $isDevMode = true;
    $paths = [__DIR__ . '/Model'];

    $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

    $connectionParams = [
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'port' => $_ENV['DB_PORT'],
        'host' => $_ENV['DB_HOST'],
        'driver' => $_ENV['DB_DRIVER'],
    ];

    // Crea la connessione DBAL
    $connection = DriverManager::getConnection($connectionParams);

    // Ora crea EntityManager passando la connessione e la configurazione
    return new EntityManager($connection, $config);
}
