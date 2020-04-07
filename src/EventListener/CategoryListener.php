<?php


namespace App\EventListener;

use App\Entity\Category;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CategoryListener
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function preUpdate(Category $category, LifecycleEventArgs $args)
    {
        if ($args->hasChangedField('image')) {
            $this->deleteFromServer($args->getOldValue('image'));
        }
    }

    public function preRemove(Category $category, LifecycleEventArgs $args)
    {
        $this->deleteFromServer($category->getImage());
    }

    private function deleteFromServer($file_name)
    {
        if (!empty($file_name)) {
            $image_category_dir = $this->parameterBag->get('image_category_dir');
            $absolute_path = $image_category_dir . "/" . $file_name;

            if (file_exists($absolute_path)) {
                unlink($absolute_path);
            }
        }
    }
}
