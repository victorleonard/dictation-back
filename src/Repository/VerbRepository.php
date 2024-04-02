<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Verb;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Verb>
 *
 * @method Verb|null find($id, $lockMode = null, $lockVersion = null)
 * @method Verb|null findOneBy(array $criteria, array $orderBy = null)
 * @method Verb[]    findAll()
 * @method Verb[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerbRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private Security $security)
    {
        parent::__construct($registry, Verb::class);
    }

    /**
     * Récupère une entrée aléatoire de la table des mots qui n'a pas été apprise par l'utilisateur courant.
     *
     * @return Verb|null
     */
    public function findRandomWord()
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser instanceof User) {
            return null; // Aucun utilisateur connecté
        }

        $queryBuilder = $this->createQueryBuilder('w');
        $queryBuilder->leftJoin('w.user', 'u');
        $queryBuilder->andWhere('u.id != :currentUserId OR u.id IS NULL');
        $queryBuilder->setParameter('currentUserId', $currentUser->getId(), UuidType::NAME);

        $verbs = $queryBuilder->getQuery()->getResult();

        if (!empty($verbs)) {
            $randomVerb = $verbs[array_rand($verbs)];
            return $randomVerb;
        }

        return null; // Aucun mot disponible pour l'utilisateur actuel
    }

    //    /**
    //     * @return Verb[] Returns an array of Verb objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Verb
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
