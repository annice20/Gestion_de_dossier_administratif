<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    /**
     * Compte le nombre total de documents dans la base de données.
     */
    public function countAll(): int
    {
        return $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte les documents selon un statut donné.
     */
    public function countByStatus(string $status): int
    {
        return $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->where('d.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    /**
     * Compte les documents reçus durant le mois en cours.
     */
    public function countReceivedThisMonth(): int
    {
        $startOfMonth = new \DateTime('first day of this month');
        $endOfMonth = new \DateTime('last day of this month');
        
        return $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->where('d.dateReception BETWEEN :start AND :end')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte les documents en retard.
     */
    public function countOverdue(): int
    {
        return $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->where('d.status = :status')
            ->andWhere('d.dueDate < :now') 
            ->setParameter('status', 'en_attente')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }
}