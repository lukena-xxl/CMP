<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\ORMException;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param null $categories
     * @param null $parentCategory
     * @param string $separator
     * @return array
     * @throws MappingException
     * @throws ORMException
     */
    public function getCategoryTree($categories = null, $parentCategory = null, $separator=' '): array
    {
        if (is_null($categories)) {
            $categories = $this->findAll();
        }

        $data = [];

        foreach ($categories as $category) {
            if ($category->getParentCategory() == $parentCategory) {
                $data[$category->getId()] = $category;

                $arr = $this->getCategoryTree($categories, $category, $separator++);

                if (!empty($arr)) {
                    foreach ($arr as $key=>$val) {
                        //$val->setName($separator.$val->getName());
                        $data[$key] = $val;
                    }
                }

                unset($categories[$category->getId()]);
            }
        }

        return $data;
    }
}
