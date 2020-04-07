<?php


namespace App\EventListener;

use App\Entity\ProductImage;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProductImageListener
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function updateProduct(ProductImage $productImage, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $product = $productImage->getProduct();

        $product->setUpdateDate(new \DateTime());

        $em->persist($product);
        $em->flush();
    }

    public function preRemove(ProductImage $productImage, LifecycleEventArgs $args)
    {
        $file_name = $productImage->getName();

        if (!empty($file_name)) {
            $image_product_dir = $this->parameterBag->get('image_product_dir');

            $absolute_path = $image_product_dir . "/" . $file_name;
            $this->deleteFromServer($absolute_path);

            $image_product_subdirs = $this->parameterBag->get('image_product_subdirs');

            foreach ($image_product_subdirs as $image_product_subdir) {
                $absolute_path = $image_product_dir . "/" . $image_product_subdir . "/" . $file_name;
                $this->deleteFromServer($absolute_path);
            }
        }
    }

    private function deleteFromServer($path)
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
