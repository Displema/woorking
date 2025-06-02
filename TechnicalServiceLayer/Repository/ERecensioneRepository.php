<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use TechnicalServiceLayer\Foundation\FEntityManager;

class ERecensioneRepository extends EntityRepository
{
    public function getRecensioneByUfficio($idufficio)
    {
        FEntityManager::getInstance();
        $em = FEntityManager::getEntityManager();
        try {
            $query = "SELECT e FROM Model\ERecensione e
            JOIN e.prenotazione p 
            JOIN p.ufficio u
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
