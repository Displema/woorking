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


    public function getActiveReservationsByOfficeDateSlot(
        EUfficio         $office,
        \DateTime        $date,
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

    public function getEntrateMensili($locatoreId): array
    {
        return $this->createQueryBuilder('p')
            ->select('SUBSTRING(p.data, 1, 7) AS mese, SUM(u.prezzo) AS entrate')
            ->join('p.ufficio', 'u')
            ->where('u.locatore = :locatoreId')
            ->groupBy('mese')
            ->setParameter('locatoreId', $locatoreId)
            ->getQuery()
            ->getArrayResult();
    }

    public function countByOffice(EUfficio $ufficio): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.ufficio = :ufficio')
            ->setParameter('ufficio', $ufficio)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
