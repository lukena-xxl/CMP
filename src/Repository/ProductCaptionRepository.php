<?php

namespace App\Repository;

use App\Entity\ProductCaption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProductCaption|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCaption|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCaption[]    findAll()
 * @method ProductCaption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCaptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCaption::class);
    }

    // /**
    //  * @return ProductCaption[] Returns an array of ProductCaption objects
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
    public function findOneBySomeField($value): ?ProductCaption
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
