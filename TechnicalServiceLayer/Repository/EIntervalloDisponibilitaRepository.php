<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\ORM\EntityRepository;
use Model\EIntervalloDisponibilita;
use Model\EUfficio;

class EIntervalloDisponibilitaRepository extends EntityRepository
{
    /**
     * @param EUfficio $ufficio
     * @return EIntervalloDisponibilita|null
     */
    public function getIntervallobyOffice(EUfficio $ufficio): ?EIntervalloDisponibilita
    {
        return $this->createQueryBuilder('s')
            ->where('s.ufficio = :uffici')
            ->setParameter('uffici', $ufficio)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
