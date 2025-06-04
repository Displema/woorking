<?php
namespace TechnicalServiceLayer\Repository;


use Doctrine\ORM\EntityRepository;


use Model\EPrenotazione;
use Model\EUfficio;
use Doctrine\Common\Collections\Collection;



class EPrenotazioneRepository extends EntityRepository
{


    /**
     * @param EUfficio $ufficio
     * @return array
     */
    public function getPrenotazioneByUfficio(EUfficio $ufficio): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.ufficio = :ufficio')
            ->setParameter('ufficio', $ufficio)
            ->getQuery()
            ->getResult();
}

    public function getActiveReservationsByOfficeDateSlot($office, $date, $fascia): int
    {
        $em = getEntityManager();
        return $em->createQueryBuilder('r')
            ->select('count(r.id)')
            ->from(EPrenotazione::class, 'r')
            ->where('r.data = :date')
            ->andWhere('r.ufficio = :office')
            ->andwhere('r.fascia = :fascia')
            ->setParameter('date', $date)
            ->setParameter('office', $office)
            ->setParameter('fascia', $fascia)
            ->getQuery()->getSingleScalarResult();

    }
}
