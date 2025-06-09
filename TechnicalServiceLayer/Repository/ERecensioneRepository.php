<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\ORM\EntityRepository;
use Exception;
use Model\ERecensione;

class ERecensioneRepository extends EntityRepository
{
    public function getRecensioneByUfficio($idufficio)
    {

        $em = getEntityManager();
        try {
            $query = "SELECT e FROM Model\ERecensione e
            JOIN e.prenotazione p 
            JOIN p.ufficio u
            WHERE u.id = :idufficio";

            $createquery = $em->createQuery($query);
            $createquery->setParameter("idufficio", $idufficio);

            return $createquery->getResult();
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
            return [];
        }
    }

    public function getRandomReviewbyLandlord($idLocatore, int $limit = 3): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('r')
            ->from(ERecensione::class, 'r')
            ->join('r.prenotazione', 'p')
            ->join('p.ufficio', 'u')
            ->join('u.locatore', 'l')
            ->where('l.id = :idLocatore')
            ->andWhere($qb->expr()->in('r.valutazione', [1, 9, 10]))
            ->setParameter('idLocatore', $idLocatore)
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }
}
