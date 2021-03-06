<?php

namespace App\Repository;

use App\Entity\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Filter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Filter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Filter[]    findAll()
 * @method Filter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Filter::class);
    }

    public function findFilterProducts($filter_id)
    {
        $qb = $this->createQueryBuilder('f');

        $qb->select(['ps.id', 'ps.name'])
            ->andWhere('f.id = :filter')
            ->setParameter('filter', $filter_id)
            ->join('f.elements', 'fe')
            ->join('fe.products', 'ps')
            ->groupBy('ps.id')
            ->orderBy('ps.id', 'ASC');

        $query = $qb->getQuery();
        return $query->execute();
    }
}
