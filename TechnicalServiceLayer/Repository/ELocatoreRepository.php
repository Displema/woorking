<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Model\ELocatore;
use Model\EProfilo;

class ELocatoreRepository extends EntityRepository
{
    /**
     * @param string $id
     * @return ELocatore|null
     */
    public function getLocatoreByUser(string $id): ?ELocatore
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('l')
            ->from(ELocatore::class, 'l')
            ->join(EProfilo::class, 'p', 'WITH', 'l.id = p.id')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
