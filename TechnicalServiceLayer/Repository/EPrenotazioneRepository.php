<?php
namespace TechnicalServiceLayer\Repository;

use DateTimeImmutable;
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

    public function getReservationsByMonths(): array
    {
        $currentYear = (int) date('Y');

        $start = new DateTimeImmutable("$currentYear-01-01");
        $end = new DateTimeImmutable("$currentYear-12-31");

        $reservations = $this->getEntityManager()->getRepository(EPrenotazione::class)->createQueryBuilder('p')
            ->where('p.data BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();

        // Initialize every month at 0
        $result = [];

        // Generate a Date Period -> Y-01-01 Y-02-01 ...
        $period = new \DatePeriod(
            new \DateTimeImmutable($start->format('Y-m-01')),
            new \DateInterval('P1M'),
            (new \DateTimeImmutable($end->format('Y-m-01')))->modify('+1 month')
        );

        foreach ($period as $date) {
            $key = $date->format('m');
            $result[$key] = 0;
        }

        // Count the reservation per month
        foreach ($reservations as $p) {
            $date = $p->getData(); // DateTime
            $key = $date->format('m');
            if (array_key_exists($key, $result)) {
                $result[$key]++;
            }
        }

        return $result;
    }
}
