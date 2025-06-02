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
     * @return EPrenotazione|null
     */
    public function getPrenotazioneByUfficio(EUfficio $ufficio): ?EPrenotazione
    {
        return $this->createQueryBuilder('p')
            ->where('p.ufficio = :ufficio')
            ->setParameter('ufficio', $ufficio)
            ->getQuery()
            ->getOneOrNullResult();
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
