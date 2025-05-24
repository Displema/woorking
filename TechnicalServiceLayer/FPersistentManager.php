<?php
namespace TechnicalServiceLayer;

use TechnicalServiceLayer\Foundation\FEntityManager;

class FPersistentManager
{
    // E' una classe singleton ovvero in tutto il progrmma esiste una sola istanza di questa classe alla quale si può accedere in maniera globale

    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function retriveobj($Eclass, $id)
    {
        return FEntityManager::getInstance()->retriveobj($Eclass, $id);
    }
    public static function uploadObj($obj): bool
    {
        return FEntityManager::getInstance()->saveObj($obj);
    }

    public static function deleteObj($obj)
    {
        return FEntityManager::getInstance()->deleteObj($obj);
    }

    public static function searchoffice($indirizzo, $date, $fascia)
    {
        return FUfficio::findbyIndirizzoDataFascia($indirizzo, $date, $fascia);
    }

    public static function getRecensione($idufficio)
    {
        return FRecensione::getRecensioneByUfficio($idufficio);
    }
}
