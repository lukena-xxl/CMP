<?php

namespace App\Repository;

use App\Entity\Orders;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    /**
     * @var User $user
     * @return mixed
     */
    public function adminOrdersList($user)
    {
        $qb = $this->createQueryBuilder('o');

        $qb->select(['o.id', 'o.created', 'u.login AS user_login', 'a.login AS admin_login'])
            ->join('o.user', 'u')
            ->join('o.admin', 'a');

        if (!in_array('ROLE_SUPERADMIN', $user->getRoles())) {
            $qb->andWhere('a.login = :login')
                ->setParameter('login', $user->getLogin());
        }

        $qb->addSelect('(SELECT SUM(op.price * op.quantity) FROM App\Entity\OrderProduct op WHERE op.in_order=o.id) AS cost')
            ->orderBy('o.id', 'DESC');

        $query = $qb->getQuery();
        return $query->execute();
    }
}
