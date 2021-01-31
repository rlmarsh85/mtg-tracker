<?php

namespace App\Repository;

use App\Entity\ColorIdentity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ColorIdentity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ColorIdentity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ColorIdentity[]    findAll()
 * @method ColorIdentity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColorIdentityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ColorIdentity::class);
    }

    // /**
    //  * @return ColorIdentity[] Returns an array of ColorIdentity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ColorIdentity
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
