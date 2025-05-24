<?php
namespace TechnicalServiceLayer;

use TechnicalServiceLayer\Foundation\FEntityManager;

class FIndirizzo
{
    public static function getAllIndirizzi()
    {
        $entity = 'EIndirizzo';
        return FEntityManager::selectobj($entity);
    }
}
