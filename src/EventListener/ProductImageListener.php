<?php


namespace App\EventListener;

use App\Entity\ProductImage;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ProductImageListener
{
    private $image_product_dir;

    public function __construct($image_product_dir)
    {
        $this->image_product_dir = $image_product_dir;
    }

    public function preRemove(ProductImage $productImage, LifecycleEventArgs $args)
    {
        $arrDir = ['800', '150', ''];

        $image_product_dir = $this->image_product_dir;
        //$image_product_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/product/';

        $file_name = $productImage->getName();

        if (!empty($file_name)) {
            foreach ($arrDir as $dir) {
                $absolute_path = $image_product_dir . $dir . "/" . $file_name;
                if (file_exists($absolute_path)) {
                    unlink($absolute_path);
                }
            }
        }
    }
}
