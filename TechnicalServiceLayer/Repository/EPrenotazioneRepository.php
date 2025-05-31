<?php
namespace TechnicalServiceLayer\Repository;


use Doctrine\ORM\EntityRepository;

use Model\EPrenotazione;
use Model\EUfficio;

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
}
