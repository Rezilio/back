<?php

namespace App\Repository;

use App\Entity\Risque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RisqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Risque::class);
    }

    public function findByNiveau(string $niveau): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.niveau = :niveau')
            ->setParameter('niveau', $niveau)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
