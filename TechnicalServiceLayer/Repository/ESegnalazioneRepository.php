<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;

class ESegnalazioneRepository extends EntityRepository
{

    public function SaveReport( $report) {
        $em =getEntityManager();
        $em->persist($report);
        $em->flush();
    }
}
