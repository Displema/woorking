<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Model\Enum\FasciaOrariaEnum;
use Model\EPrenotazione;
use Model\EUfficio;

class EPrenotazioneRepository extends EntityRepository
{

    public function getActiveReservationsByOfficeDateSlot(
        EUfficio $office,
        \DateTime $date,
        FasciaOrariaEnum $fascia
    ): int {
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
