<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Model\Enum\StatoUfficioEnum;
use Model\EPrenotazione;
use Model\EUfficio;
use Ramsey\Uuid\Uuid;
use TechnicalServiceLayer\Foundation\FEntityManager;

class EUfficioRepository extends EntityRepository
{
    public function findByIndirizzoDataFascia($indirizzo, $fascia, $date)
    {
        $em = getEntityManager();

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
    public function findbythree($queryString, $date, $fascia): mixed
    {
        $em = $this->getEntityManager();
        try {
            $query = "SELECT e FROM Model\EUfficio e 
                     JOIN e.intervalliDisponibilita  idisp
                     JOIN e.indirizzo  indirizzo
                     WHERE ( indirizzo.via = :query
                     or indirizzo.citta = :query
                     or indirizzo.provincia = :query
                     or e.titolo = :query)
                     AND idisp.dataInizio <= :data
                     AND idisp.dataFine >= :data   
                      AND idisp.fascia = :fascia";
            $createquery = $em->createQuery($query);
            $createquery->setParameter("query", $queryString);
            $createquery->setParameter("data", $date);
            $createquery->setParameter("fascia", $fascia);
            return $createquery->getResult();
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    /**
     * @param EUfficio $office
     * @return Collection<int, EPrenotazione>
     * */
    public function getActiveReservations(EUfficio $office): Collection
    {
        $entity_manager = $this->getEntityManager();

        $reservationsRepo = $entity_manager->getRepository(EPrenotazione::class);
        $reservations = $reservationsRepo->findBy(['ufficio'=> $office]);
        return new ArrayCollection($reservationsRepo->createQueryBuilder('e')
            ->where('e.data > :data')
            ->andWhere('e.ufficio = :ufficio')
            ->setParameter('ufficio', $office)
            ->setParameter('data', new \DateTime())
            ->getQuery()
            ->getResult());
    }

    /**
     * Returns all the office that satisfies
     * @param StatoUfficioEnum $state
     * @return Collection<int, EUfficio>
     */
    public function getOfficesByState(StatoUfficioEnum $state): Collection
    {
        return new ArrayCollection($this->getEntityManager()->createQueryBuilder()
            ->update(EUfficio::class, 'e')
            ->select('e')
            ->where('e.stato = :stato')
            ->setParameter('stato', $state->value)
            ->getQuery()
            ->getResult());
    }

    public function getOfficeByLocatore( $id): Collection
    {
        return new \Doctrine\Common\Collections\ArrayCollection(
            $this->createQueryBuilder('u')
                ->where('u.locatore = :id')
                ->andWhere('u.stato = :stato')
                ->setParameter('id', $id)
                ->setParameter('stato', 'Approvato')
                ->getQuery()
                ->getResult()
        );
    }

}
