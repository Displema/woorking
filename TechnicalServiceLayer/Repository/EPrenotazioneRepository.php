<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Model\EPrenotazione;
use TechnicalServiceLayer\Foundation\FEntityManager;

class EPrenotazioneRepository extends EntityRepository
{



    public function CountByOfficeDateFascia($office,$date,$fascia){
        $em = getEntityManager();
        return $em->createQueryBuilder('r')
            ->select('count(r.id)')
            ->from('Model\EPrenotazione','r')
            ->where('r.data = :date')
            ->andWhere('r.ufficio = :office')
            ->andwhere('r.fascia = :fascia')
            ->setParameter('date',$date)
            ->setParameter('office',$office)
            ->setParameter('fascia',$fascia)
            ->getQuery()->getSingleScalarResult();

}



}
