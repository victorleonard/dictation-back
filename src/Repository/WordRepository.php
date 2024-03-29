<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Word;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Word>
 *
 * @method Word|null find($id, $lockMode = null, $lockVersion = null)
 * @method Word|null findOneBy(array $criteria, array $orderBy = null)
 * @method Word[]    findAll()
 * @method Word[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private Security $security,)
    {
        parent::__construct($registry, Word::class);
    }

    /**
     * Récupère une entrée aléatoire de la table des mots qui n'a pas été apprise par l'utilisateur courant.
     *
     * @return Word|null
     */
    public function findRandomWord()
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser instanceof User) {
            return null; // Aucun utilisateur connecté
        }

        $queryBuilder = $this->createQueryBuilder('w');
        $queryBuilder->leftJoin('w.users', 'u');
        $queryBuilder->andWhere('u.id != :currentUserId OR u.id IS NULL');
        $queryBuilder->setParameter('currentUserId', $currentUser->getId(), UuidType::NAME);

        $words = $queryBuilder->getQuery()->getResult();

        if (!empty($words)) {
            $randomWord = $words[array_rand($words)];
            return $randomWord;
        }

        return null; // Aucun mot disponible pour l'utilisateur actuel
    }

    //    /**
    //     * @return Word[] Returns an array of Word objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Word
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
