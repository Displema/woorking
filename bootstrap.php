<?php

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

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
