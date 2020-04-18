<?php

namespace App\Repository;

use App\Entity\Rss;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Rss|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rss|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rss[]    findAll()
 * @method Rss[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RssRepository extends ServiceEntityRepository
{
    public function  __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rss::class);
    }

    // /**
    //  * @return Rss[] Returns an array of Rss objects
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
    public function findOneBySomeField($value): ?Rss
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
