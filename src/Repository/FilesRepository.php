<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Files;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Files|null find($id, $lockMode = null, $lockVersion = null)
 * @method Files|null findOneBy(array $criteria, array $orderBy = null)
 * @method Files[]    findAll()
 * @method Files[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Files::class);
    }

    // /**
    //  * @return Files[] Returns an array of Files objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Files
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findUserActiveDocuments(User $user): ?array
    {
        return $this->createQueryBuilder('f')
            ->andWhere(':company MEMBER OF f.pharmacies')
            ->setParameter('company', $user->getCompany())
            ->andWhere('f.isActive = true')
            ->andWhere('f.deletedAt >= CURRENT_TIMESTAMP() or f.deletedAt IS NULL')
            ->orderBy('f.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
