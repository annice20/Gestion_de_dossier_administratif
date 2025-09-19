<?php

namespace App\Repository;

use App\Entity\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Request::class);
    }

    /**
     * Archive automatiquement les requêtes plus anciennes que $days jours
     * Change le statut en 'archived' et retourne le nombre de lignes mises à jour
     */
    public function archiveOldRequests(int $days): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->update(Request::class, 'r')
            ->set('r.statut', ':archived')
            ->where('r.createdAt < :date')
            ->setParameter('archived', 'archived')
            ->setParameter('date', new \DateTime("-{$days} days"));

        return $qb->getQuery()->execute();
    }

    // Compte toutes les requêtes
    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Compte les requêtes selon leur statut
    public function countByStatus(string $status): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.statut = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Compte les requêtes d'une date précise
    public function countByDate(\DateTimeInterface $date): int
    {
        $start = (clone $date)->setTime(0,0,0);
        $end = (clone $date)->setTime(23,59,59);

        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Retourne toutes les requêtes avec le statut "archivé".
     *
     * @return Request[]
     */
    public function findArchives(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.statut = :statut')
            ->setParameter('statut', 'archivé') // ⚠️ attention : "archivé" et non "archived" selon ton choix
            ->orderBy('r.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
