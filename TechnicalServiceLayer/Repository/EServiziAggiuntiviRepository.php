<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Model\EServiziAggiuntivi;
use Model\EUfficio;

class EServiziAggiuntiviRepository extends EntityRepository
{

    /**
     * @param EUfficio $ufficio
     * @return EServiziAggiuntivi|null
     */
    public function getServiziAggiuntivibyOffice(EUfficio $ufficio): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.ufficio = :ufficio')
            ->setParameter('ufficio', $ufficio)
            ->getQuery()
            ->getResult();

    }
}
