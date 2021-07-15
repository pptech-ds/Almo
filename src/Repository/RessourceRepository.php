<?php

namespace App\Repository;

use App\Entity\Ressource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ressource|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ressource|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ressource[]    findAll()
 * @method Ressource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RessourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ressource::class);
    }

    // /**
    //  * @return Ressource[] Returns an array of Ressource objects
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
    public function findOneBySomeField($value): ?Ressource
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    /**
     * Returns last 6 Ressources by category
     * @return Ressource[] Returns an array of Article objects 
     */
    public function findLastRessourcesByCategory(string $categoryName = null, int $maxResult = 6)
    {
        $query = $this->createQueryBuilder('r')
            ->select('r')
            ->innerJoin('r.ressourceCategory', 'c')
            ->andWhere('c.name = :name', 'r.active = :active')
            ->setParameter('name', $categoryName)
            ->setParameter('active', true)
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults($maxResult)
            ;

        // dd($query->getQuery()->getResult());
        
        return $query->getQuery()->getResult();
    }



    /**
     * Returns All Ressources by category
     * @return Ressource[] Returns an array of Article objects 
     */
    public function findAllRessourcesByCategory(string $categoryName = null)
    {
        $query = $this->createQueryBuilder('r')
            ->select('r')
            ->innerJoin('r.ressourceCategory', 'c')
            ->andWhere('c.name = :name', 'r.active = :active')
            ->setParameter('name', $categoryName)
            ->setParameter('active', true)
            ->orderBy('r.createdAt', 'DESC')
            ;

        // dd($query->getQuery()->getResult());
        
        return $query->getQuery()->getResult();
    }
}
