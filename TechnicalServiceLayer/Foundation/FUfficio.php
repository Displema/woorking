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
}
