<?php

namespace App\Repository;

use App\Entity\WebinarQuestions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebinarQuestions|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebinarQuestions|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebinarQuestions[]    findAll()
 * @method WebinarQuestions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebinarQuestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebinarQuestions::class);
    }

    // /**
    //  * @return WebinarQuestions[] Returns an array of WebinarQuestions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebinarQuestions
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
