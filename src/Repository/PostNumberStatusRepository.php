<?php

namespace App\Repository;

use App\Entity\PostNumberStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PostNumberStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostNumberStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostNumberStatus[]    findAll()
 * @method PostNumberStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostNumberStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostNumberStatus::class);
    }

    // /**
    //  * @return PostNumberStatus[] Returns an array of PostNumberStatus objects
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
    public function findOneBySomeField($value): ?PostNumberStatus
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
