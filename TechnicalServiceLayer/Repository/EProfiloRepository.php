<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Model\EProfilo;

class EProfiloRepository extends EntityRepository
{
    public function getSignupByMonths(): array
    {
        {
            $currentYear = (int)date('Y');

            $start = new \DateTimeImmutable("$currentYear-01-01");
            $end = new \DateTimeImmutable("$currentYear-12-31");

            /** @var Collection<int, EProfilo> $profiles */
            $profiles = $this->getEntityManager()->getRepository(EProfilo::class)->createQueryBuilder('p')
                ->andWhere('p.createdAt BETWEEN :start AND :end')
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->getQuery()
                ->getResult();

            // Initialize every month at 0
            $result = [];

            // Generate a Date Period -> Y-01-01 Y-02-01 ...
            $period = new \DatePeriod(
                new \DateTimeImmutable($start->format('Y-m-01')),
                new \DateInterval('P1M'),
                (new \DateTimeImmutable($end->format('Y-m-01')))->modify('+1 month')
            );

        foreach ($period as $date) {
            $key = $date->format('m');
            $result[$key] = 0;
        }

            // Count the profiles per month
        foreach ($profiles as $p) {
            $date = $p->getCreatedAt(); // DateTime
            $key = $date->format('m');
            if (array_key_exists($key, $result)) {
                $result[$key]++;
            }
        }

            return $result;
        }
    }
}
