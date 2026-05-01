<?php

namespace App\Repository;

use App\Entity\Incident;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IncidentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Incident::class);
    }

    public function findOuverts(): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.statut IN (:statuts)')
            ->setParameter('statuts', ['ouvert', 'en_cours'])
            ->orderBy('i.dateDetection', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
