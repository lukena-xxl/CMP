<?php

namespace App\Repository;

use App\Entity\Coefficient;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Coefficient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coefficient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coefficient[]    findAll()
 * @method Coefficient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoefficientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coefficient::class);
    }

    /**
     * @var User $user
     * @return mixed
     */
    public function adminCoefficientsList($user)
    {
        $qb = $this->createQueryBuilder('c');

        if (!in_array('ROLE_SUPERADMIN', $user->getRoles())) {
            $qb->join('c.user', 'u');
            $qb->andWhere('u.login = :login')
                ->setParameter('login', $user->getLogin());
        }

        $qb->orderBy('c.id', 'DESC');

        $query = $qb->getQuery();
        return $query->execute();
    }
}
