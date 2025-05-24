<?php
namespace TechnicalServiceLayer;

class FIndirizzo
{
    public static function getAllIndirizzi()
    {
        $entity = 'EIndirizzo';
        return FEntityManager::selectobj($entity);
    }
}
