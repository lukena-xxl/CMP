<?php

namespace App\Repository;

use App\Entity\ProductCaption;
use App\Entity\User;
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

    /**
     * @var User $user
     * @return mixed
     */
    public function adminProductCaptionsList($user)
    {
        $qb = $this->createQueryBuilder('pc');

        if (!in_array('ROLE_SUPERADMIN', $user->getRoles())) {
            $qb->join('pc.user', 'u');
            $qb->andWhere('u.login = :login')
                ->setParameter('login', $user->getLogin());
        }

        $qb->orderBy('pc.position', 'ASC');

        $query = $qb->getQuery();
        return $query->execute();
    }
}
