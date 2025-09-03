<?php

namespace App\Repository;

use App\Entity\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Request>
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Request::class);
    }

    public function findArchives(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.statut = :statut')
            ->setParameter('statut', 'archivé')
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function archiveOldRequests(int $days = 30): int
    {
        $dateLimit = new \DateTimeImmutable('-' . $days . ' days');
        
        $query = $this->createQueryBuilder('r')
            ->update()
            ->set('r.statut', ':archivedStatus')
            ->set('r.updatedAt', ':now')
            ->where('r.createdAt < :dateLimit')
            ->andWhere('r.statut IN (:completedStatuses)')
            ->setParameter('archivedStatus', 'archivé')
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('dateLimit', $dateLimit)
            ->setParameter('completedStatuses', ['traite', 'complet'])
            ->getQuery();

        return $query->execute();
    }

    public function countArchivedRequests(): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.statut = :statut')
            ->setParameter('statut', 'archivé')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findArchivableRequests(int $days = 30): array
{
    $dateLimit = new \DateTimeImmutable('-' . $days . ' days');
    
    return $this->createQueryBuilder('r')
        ->where('r.createdAt < :dateLimit')
        ->andWhere('r.statut IN (:statuses)')
        ->setParameter('dateLimit', $dateLimit)
        ->setParameter('statuses', ['traite', 'complet'])
        ->orderBy('r.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
}
}