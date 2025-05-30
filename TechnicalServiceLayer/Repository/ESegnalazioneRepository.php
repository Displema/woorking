<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use TechnicalServiceLayer\Foundation\FEntityManager;

class ESegnalazioneRepository extends EntityRepository
{

    public function SaveReport( $report) {
        $em = FEntityManager::getInstance()->getEntityManager();
        $em->persist($report);
        $em->flush();
    }
}
