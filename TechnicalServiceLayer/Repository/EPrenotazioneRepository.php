<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Model\EPrenotazione;
use TechnicalServiceLayer\Foundation\FEntityManager;

class EPrenotazioneRepository extends EntityRepository
{

    public function savePrenotation( EPrenotazione $prenotazione ){
        $em = FEntityManager::getInstance()->getEntityManager();
        $em->persist($prenotazione); // inserisce in "pending"
        $em->flush();
    }



}
