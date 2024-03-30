<?php

namespace App\Repository;

use App\Entity\WordError;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WordError>
 *
 * @method WordError|null find($id, $lockMode = null, $lockVersion = null)
 * @method WordError|null findOneBy(array $criteria, array $orderBy = null)
 * @method WordError[]    findAll()
 * @method WordError[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordErrorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WordError::class);
    }

    //    /**
    //     * @return WordError[] Returns an array of WordError objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?WordError
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
