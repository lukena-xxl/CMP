<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @var User $user
     * @return mixed
     */
    public function adminProductsList($user = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select(['p.id', 'p.name', 'p.slug', 'p.is_visible'])
            ->join('p.category', 'cat')->addSelect(['cat.id as category_id', 'cat.name as category_name', 'cat.slug as category_slug'])
            ->join('p.availability', 'a')->addSelect(['a.name as availability_name', 'a.color as availability_color'])
            ->join('p.currency', 'cur')->addSelect('cur.abbr as currency_name')
            ->join('p.user', 'u');

        if ($user && !in_array('ROLE_SUPERADMIN', $user->getRoles())) {
            $qb->andWhere('u.login = :login')
                ->setParameter('login', $user->getLogin());
        }

        $qb->addSelect('u.login as user_login')
            ->join('p.images', 'img')->andWhere('img.is_main = 1')->addSelect(['img.name as image_name', 'img.is_visible as image_is_visible'])
            ->addSelect('(SELECT SUM((i.price * IF(i.coefficient IS NOT NULL, (SELECT c.ratio FROM App\Entity\Coefficient c WHERE c.id=i.coefficient), 1)) * (IF(i.discount_percentage IS NOT NULL AND ((i.discount_start_date IS NULL OR UNIX_TIMESTAMP(i.discount_start_date) <= UNIX_TIMESTAMP()) AND (i.discount_end_date IS NULL OR UNIX_TIMESTAMP(i.discount_end_date) >= UNIX_TIMESTAMP())), 1 - i.discount_percentage / 100, 1)) * i.displayed_quantity) FROM App\Entity\ProductItem i WHERE i.product=p.id AND i.is_checked=1 AND i.is_visible=1) as cost')
            ->orderBy('p.id', 'DESC');

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findProductFilters($product_id)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select(['f.id', 'f.name'])
            ->andWhere('p.id = :product')
            ->setParameter('product', $product_id)
            ->join('p.filter_elements', 'fe')
            ->join('fe.filter', 'f')
            ->groupBy('f.id')
            ->orderBy('f.position', 'ASC');

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findProductFilterElements($filter_id, $product_id)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select(['fe.name'])
            ->andWhere('p.id = :product')
            ->setParameter('product', $product_id)
            ->join('p.filter_elements', 'fe')
            ->join('fe.filter', 'f')
            ->andWhere('f.id = :filter')
            ->setParameter('filter', $filter_id)
            ->orderBy('fe.position', 'ASC');

        $query = $qb->getQuery();
        return $query->execute();
    }

    /**
     * @param $text
     * @var User $user
     * @return mixed
     */
    public function searchProducts($text, $user = null)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where(
            $qb->expr()->like('p.name', ':text')
        )->setParameter('text', '%' . $text . '%');

        if ($user) {
            $qb->join('p.user', 'u')
                ->andWhere('u.login = :login')
                ->setParameter('login', $user->getLogin());
        }

        $query = $qb->getQuery();
        return $query->execute();
    }
}
