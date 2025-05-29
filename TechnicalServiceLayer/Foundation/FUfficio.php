<?php
namespace TechnicalServiceLayer\Foundation;

use Exception;
use Model\EUfficio;
use TechnicalServiceLayer\Foundation\FEntityManager;

class FUfficio
{

    public static function findByIndirizzoDataFascia($indirizzo, $fascia, $date)
    {
        $em = FEntityManager::getEntityManager();

        try {
            $qb = $em->createQueryBuilder();

            $qb->select('e')
                ->from(EUfficio::class/** @type EUfficio */, 'e')
                ->join('e.intervalliDisponibilita', 'idisp')
                ->join('e.indirizzo', 'indirizzo')
                ->where('indirizzo.via = :indirizzo')
                ->andWhere('idisp.fascia = :fascia')
                ->andWhere('idisp.dataInizio <= :data')
                ->andWhere('idisp.dataFine >= :data')
                ->setParameter('indirizzo', $indirizzo)
                ->setParameter('fascia', $fascia)
                ->setParameter('data', $date);

            return $qb->getQuery()->getResult();
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    public static function  findby($indirizzo, $date,$fascia): mixed
    {
        $em = FEntityManager::getInstance()->getEntityManager();
        try {
            $query = "SELECT e FROM Model\EUfficio e 
                     JOIN e.intervalliDisponibilita  idisp
                     JOIN e.indirizzo  indirizzo
                     WHERE indirizzo.via = :indirizzo
                     AND idisp.dataInizio <= :data
                     AND idisp.dataFine >= :data   
                      AND idisp.fascia = :fascia";
            $createquery = $em->createQuery($query);
            $createquery->setParameter("indirizzo", $indirizzo);
            $createquery->setParameter("data", $date);
            $createquery->setParameter("fascia", $fascia);
            return $createquery->getResult();

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }

    }
}
