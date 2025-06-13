<?php
namespace TechnicalServiceLayer\Repository;

use DateTime;
use Doctrine\Common\Collections\Collection;
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

    public function isDateValid(EUfficio $ufficio, DateTime $data): bool
    {
        /** @var Collection<int, EIntervalloDisponibilita> $ranges */
        $ranges = $ufficio->getIntervalliDisponibilita();

        // If the office doesn't contain any interval return false
        if ($ranges->isEmpty()) {
            return false;
        }

        // Filter the interval containing the date
        /** @var EIntervalloDisponibilita $range */
        $intervalliValidi = $ranges->filter(function ($range) use ($data) {
            return $range->getDataInizio() <= $data && $range->getDataFine() >= $data;
        });

        // Return true if at least one interval contains the date
        return !$intervalliValidi->isEmpty();
    }
}
