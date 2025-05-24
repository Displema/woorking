<?php
namespace TechnicalServiceLayer;

use Woorking\TechnicalServiceLayer\Foundation\FEntityManager;
class FPersistentManager{
    // E' una classe singleton ovvero in tutto il progrmma esiste una sola istanza di questa classe alla quale si può accedere in maniera globale

    private static $instance;

    private function __construct(){
    }

    public static function getInstance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

        public static function retriveobj($Eclass, $id){
            return FEntityManager::getInstance()->retriveobj($Eclass, $id);
        }
         public static function uploadObj($obj): bool
         {
            return FEntityManager::getInstance()->saveObj($obj);
         }

         public static function deleteObj($obj){
            $result=FEntityManager::getInstance()->deleteObj($obj);
            return $result;
        }

        public static function searchoffice($indirizzo,$date,$fascia){
            $result=FUfficio::findbyIndirizzoDataFascia($indirizzo,$date,$fascia);
            return $result;
        }

        public static function getRecensione($idufficio){
            $result=FRecensione::getRecensioneByUfficio($idufficio);    
            return $result;
        }
}