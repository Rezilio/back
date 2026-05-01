<?php

namespace App\Repository;

use App\Entity\MesureConformite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MesureConformiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MesureConformite::class);
    }

    public function findByModule(string $module): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.module = :module')
            ->setParameter('module', $module)
            ->orderBy('m.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countByStatut(): array
    {
        return $this->createQueryBuilder('m')
            ->select('m.statut, COUNT(m.id) as total')
            ->groupBy('m.statut')
            ->getQuery()
            ->getResult();
    }
}
