<?php
namespace TechnicalServiceLayer\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Model\EFoto;
use Model\EUfficio;
use Doctrine\Common\Collections\Collection;

class EFotoRepository extends EntityRepository
{
    public function getFotoById($id): ?EFoto
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->andWhere('p.attivo = true')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param EUfficio $ufficio
     * @return Collection<int, EFoto>
     */
    public function getAllPhotosByOffice(EUfficio $ufficio): Collection
    {
        $result = $this->createQueryBuilder('p')
            ->where('p.ufficio = :ufficio')
            ->setParameter('ufficio', $ufficio)
            ->getQuery()
            ->getResult();

        return new ArrayCollection($result);
    }


    /**
     * @param EUfficio $ufficio
     * @return EFoto|null
     */
    public function getFirstPhotoByOffice(EUfficio $ufficio): ?EFoto
    {
        return $this->createQueryBuilder('p')
            ->where('p.ufficio = :ufficio')
            ->setParameter('ufficio', $ufficio)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
