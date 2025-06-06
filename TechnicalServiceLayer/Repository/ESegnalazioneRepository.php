<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Model\ELocatore;
use Model\Enum\ReportStateEnum;
use Model\EProfilo;
use Model\ESegnalazione;

class ESegnalazioneRepository extends EntityRepository
{

    /**
     * @param ELocatore $user
     * @return Collection<int, ESegnalazione>
     */
    public function getReportsByLandlord(ELocatore $user): Collection
    {
        return new ArrayCollection(
            $this->getEntityManager()->createQueryBuilder()
            ->select('r')
            ->from(ESegnalazione::class, 'r')
            ->join('r.ufficio', 'u')
            ->where('u.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        );
    }

    /**
     * @param ReportStateEnum $state
     * @return Collection<int, ESegnalazione>
     */
    public function findAllByState(ReportStateEnum $state): Collection
    {
        return new ArrayCollection(
            $this->getEntityManager()->createQueryBuilder()
            ->select('r')
            ->from(ESegnalazione::class, 'r')
            ->where('r.state = :state')
            ->setParameter('state', $state)
            ->getQuery()
            ->getResult()
        );
    }

    public function findAllByUser(EProfilo $user): Collection
    {
        return new ArrayCollection(
            $this->getEntityManager()->createQueryBuilder()
            ->select('r')
            ->from(ESegnalazione::class, 'r')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        );
    }
}
