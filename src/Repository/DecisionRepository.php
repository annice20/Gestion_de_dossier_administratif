<?php

namespace App\Repository;

use App\Entity\Decision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Decision>
 */
class DecisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Decision::class);
    }

    //    /**
    //     * @return Decision[] Returns an array of Decision objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Decision
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getAllDecisions(): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT 
                r.ref,
                c.nom,
                c.prenoms,
                d.resultat,
                d.motif,
                d.valide_par,
                d.valide_le
            FROM citizen_profile c
            JOIN request r ON c.id = r.demandeur_id
            JOIN decision d ON r.id = d.request_id
        ";

        return $connection->fetchAllAssociative($sql);
    }
}
