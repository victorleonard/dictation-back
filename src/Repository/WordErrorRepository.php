<?php

namespace App\Repository;

use App\Entity\WordError;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

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

    /**
     * Récupère une entrée aléatoire de la table des mots qui n'a pas été apprise par l'utilisateur courant.
     *
     * @return Word|null
     */
    public function findErrorByUser($id)
    {
        $queryBuilder = $this->createQueryBuilder('w');
        $queryBuilder->leftJoin('w.user', 'u');
        $queryBuilder->andWhere('u.id = :userId');
        $queryBuilder->setParameter('userId', $id, UuidType::NAME);

        $words = $queryBuilder->getQuery()->getResult();

        if (!empty($words)) {
            return $words;
        }

        return null; // Aucun mot disponible pour l'utilisateur actuel
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
