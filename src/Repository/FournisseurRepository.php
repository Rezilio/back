<?php

namespace App\Repository;

use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

    public function findCritiques(): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.criticite = :criticite')
            ->setParameter('criticite', 'critique')
            ->orderBy('f.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
