<?php

namespace App\Repository;

use App\Entity\PostNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PostNumber|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostNumber|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostNumber[]    findAll()
 * @method PostNumber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostNumberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostNumber::class);
    }

    // /**
    //  * @return PostNumber[] Returns an array of PostNumber objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostNumber
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
