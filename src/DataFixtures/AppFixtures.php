<?php


namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function load(ObjectManager $manager)
    {
        $dirs = [
            $this->parameterBag->get('image_product_dir'),
            $this->parameterBag->get('image_item_dir'),
            $this->parameterBag->get('image_category_dir'),
            $this->parameterBag->get('image_article_dir'),
            $this->parameterBag->get('image_order_dir')
        ];

        if (!empty($this->parameterBag->get('image_product_subdirs'))) {
            foreach ($this->parameterBag->get('image_product_subdirs') as $path) {
                $dirs[] = $this->parameterBag->get('image_product_dir') . '/' . $path;
            }
        }

        foreach ($dirs as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir);
            }
        }
    }
}
