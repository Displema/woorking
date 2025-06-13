<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\ORM\EntityRepository;
use Model\EIntervalloDisponibilita;
use Model\EUfficio;

class EIntervalloDisponibilitaRepository extends EntityRepository
{
    /**
     * @param EUfficio $ufficio
     * @return array
     */
    public function getIntervallobyOffice(EUfficio $ufficio): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.ufficio = :uffici')
            ->setParameter('uffici', $ufficio)
            ->getQuery()
            ->getResult();
    }
}
