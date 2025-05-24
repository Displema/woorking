<?php
namespace TechnicalServiceLayer;

use Woorking\TechnicalServiceLayer\Foundation\FEntityManager;

class FIndirizzo
{
    public static function getAllIndirizzi()
    {
        $entity = 'EIndirizzo';
        return FEntityManager::selectobj($entity);
    }
}
