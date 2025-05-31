<?php
namespace TechnicalServiceLayer\Foundation;

use Exception;
use TechnicalServiceLayer\Foundation\FEntityManager;

class FRecensione
{

    public static function getRecensioneByUfficio($idufficio)
    {
        FEntityManager::getInstance();
        $em = FEntityManager::getEntityManager();
        try {
            $query = "SELECT e FROM Model\ERecensione e
            JOIN e.idPrenotazione p 
            JOIN p.idUfficio u
            WHERE u.id = :idufficio";
        
            $createquery = $em->createQuery($query);
            $createquery->setParameter("idufficio", $idufficio);

            return $createquery->getResult();
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
            return [];
        }
    }
}
