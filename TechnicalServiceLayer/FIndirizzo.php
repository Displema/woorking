<?php
require_once 'C:/Users/39327/Desktop/UFFICI/TechnicalServiceLayer/foundation/FEntityManager.php';
require_once 'C:/Users/39327/Desktop/UFFICI/Entity/EIndirizzo.php';
class FIndirizzo
{
    public static function getAllIndirizzi()
    {
        $entity = 'EIndirizzo'; 
        return FEntityManager::selectobj($entity);
    }
}