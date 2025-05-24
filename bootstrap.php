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
        'dbname' => 'dev_woorking',
        'user' => 'root',
        'password' => '',
        'port' => '3306',
        'host' => '127.0.0.1',
        'driver' => 'pdo_mysql',
    ];

    // Crea la connessione DBAL
    $connection = DriverManager::getConnection($connectionParams);

    // Ora crea EntityManager passando la connessione e la configurazione
    return new EntityManager($connection, $config);
}
