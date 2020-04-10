<?php

namespace App\Repository;

use App\Entity\FilterElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FilterElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method FilterElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method FilterElement[]    findAll()
 * @method FilterElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilterElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FilterElement::class);
    }
}
