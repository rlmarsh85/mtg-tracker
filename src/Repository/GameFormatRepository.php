<?php

namespace App\Repository;

use App\Entity\GameFormat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameFormat|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameFormat|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameFormat[]    findAll()
 * @method GameFormat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameFormatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameFormat::class);
    }

    // /**
    //  * @return GameFormat[] Returns an array of GameFormat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameFormat
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
