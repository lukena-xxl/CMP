<?php


namespace App\EventListener;

use App\Entity\ProductItem;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProductItemListener
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function updateProduct(ProductItem $productItem, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $product = $productItem->getProduct();

        $product->setUpdateDate(new \DateTime());

        $em->persist($product);
        $em->flush();
    }

    public function preRemove(ProductItem $productItem, LifecycleEventArgs $args)
    {
        $this->deleteFromServer($productItem->getImg());
    }

    public function preUpdate(ProductItem $productItem, LifecycleEventArgs $args)
    {
        if ($args->hasChangedField('img')) {
            $this->deleteFromServer($args->getOldValue('img'));
        }
    }

    private function deleteFromServer($file_name)
    {
        if (!empty($file_name)) {
            $image_item_dir = $this->parameterBag->get('image_item_dir');
            $absolute_path = $image_item_dir . "/" . $file_name;

            if (file_exists($absolute_path)) {
                unlink($absolute_path);
            }
        }
    }
}
