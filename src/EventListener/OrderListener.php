<?php


namespace App\EventListener;

use App\Entity\OrderProduct;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OrderListener
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function postPersist(OrderProduct $orderProduct, LifecycleEventArgs $args)
    {
        $image = $orderProduct->getImage();

        if (!empty($image)) {
            $image_item_dir = $this->parameterBag->get('image_item_dir');
            $absolute_path_item = $image_item_dir . "/" . $image;
            if (file_exists($absolute_path_item)) {
                $image_order_dir = $this->parameterBag->get('image_order_dir');
                $absolute_path_order = $image_order_dir . "/" . $image;

                copy($absolute_path_item, $absolute_path_order);
            }
        }
    }

    public function preRemove(OrderProduct $orderProduct, LifecycleEventArgs $args)
    {
        $file_name = $orderProduct->getImage();

        if (!empty($file_name)) {
            $image_order_dir = $this->parameterBag->get('image_order_dir');
            $absolute_path = $image_order_dir . "/" . $file_name;

            if (file_exists($absolute_path)) {
                unlink($absolute_path);
            }
        }
    }
}
