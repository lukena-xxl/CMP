<?php

namespace App\Repository;

use App\Entity\ProductParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProductParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductParameter[]    findAll()
 * @method ProductParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductParameter::class);
    }

    // /**
    //  * @return ProductParameter[] Returns an array of ProductParameter objects
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
    public function findOneBySomeField($value): ?ProductParameter
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
