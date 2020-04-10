<?php

namespace App\Repository;

use App\Entity\Product;
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

    public function adminProductsList()
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select(['p.id', 'p.name', 'p.slug', 'p.is_visible'])
            ->join('p.category', 'cat')->addSelect(['cat.id as category_id', 'cat.name as category_name', 'cat.slug as category_slug'])
            ->join('p.availability', 'a')->addSelect(['a.name as availability_name', 'a.color as availability_color'])
            ->join('p.currency', 'cur')->addSelect('cur.abbr as currency_name')
            ->join('p.user', 'u')->addSelect('u.login as user_login')
            ->join('p.images', 'img')->andWhere('img.is_main = 1')->addSelect(['img.name as image_name', 'img.is_visible as image_is_visible'])
            ->addSelect('(SELECT SUM((i.price * IF(i.coefficient IS NOT NULL, (SELECT c.ratio FROM App\Entity\Coefficient c WHERE c.id=i.coefficient), 1)) * (IF(i.discount_percentage IS NOT NULL AND ((i.discount_start_date IS NULL OR UNIX_TIMESTAMP(i.discount_start_date) <= UNIX_TIMESTAMP()) AND (i.discount_end_date IS NULL OR UNIX_TIMESTAMP(i.discount_end_date) >= UNIX_TIMESTAMP())), 1 - i.discount_percentage / 100, 1)) * i.displayed_quantity) FROM App\Entity\ProductItem i WHERE i.product=p.id AND i.is_checked=1 AND i.is_visible=1) as cost')
            ->orderBy('p.id', 'DESC');

        $query = $qb->getQuery();
        return $query->execute();
    }
}
