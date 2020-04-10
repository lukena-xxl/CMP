<?php


namespace App\EventListener;

use App\Entity\Article;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ArticleListener
{
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function preUpdate(Article $article, LifecycleEventArgs $args)
    {
        if ($args->hasChangedField('image')) {
            $this->deleteFromServer($args->getOldValue('image'));
        }
    }

    public function preRemove(Article $article, LifecycleEventArgs $args)
    {
        $this->deleteFromServer($article->getImage());
    }

    private function deleteFromServer($file_name)
    {
        if (!empty($file_name)) {
            $image_article_dir = $this->parameterBag->get('image_article_dir');
            $absolute_path = $image_article_dir . "/" . $file_name;

            if (file_exists($absolute_path)) {
                unlink($absolute_path);
            }
        }
    }
}
