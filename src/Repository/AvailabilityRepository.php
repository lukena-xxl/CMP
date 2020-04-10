<?php

namespace App\Repository;

use App\Entity\Availability;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Availability|null find($id, $lockMode = null, $lockVersion = null)
 * @method Availability|null findOneBy(array $criteria, array $orderBy = null)
 * @method Availability[]    findAll()
 * @method Availability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Availability::class);
    }

    /**
     * @var User $user
     * @return mixed
     */
    public function adminAvailabilitiesList($user)
    {
        $qb = $this->createQueryBuilder('a');

        if (!in_array('ROLE_SUPERADMIN', $user->getRoles())) {
            $qb->join('a.user', 'u');
            $qb->andWhere('u.login = :login')
                ->setParameter('login', $user->getLogin());
        }

        $qb->orderBy('a.id', 'DESC');

        $query = $qb->getQuery();
        return $query->execute();
    }
}
