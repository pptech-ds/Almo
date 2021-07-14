<?php

namespace App\Repository;

use App\Entity\WebinarCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebinarCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebinarCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebinarCategory[]    findAll()
 * @method WebinarCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebinarCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebinarCategory::class);
    }

    // /**
    //  * @return WebinarCategory[] Returns an array of WebinarCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebinarCategory
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
