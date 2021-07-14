<?php

namespace App\Repository;

use App\Entity\Webinar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Webinar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Webinar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Webinar[]    findAll()
 * @method Webinar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebinarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Webinar::class);
    }

    // /**
    //  * @return Webinar[] Returns an array of Webinar objects
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
    public function findOneBySomeField($value): ?Webinar
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
