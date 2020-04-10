<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller\Fronted
 * @Route("/category", name="frontend_category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("", name="_all")
     */
    public function categoryAll()
    {
        return $this->render('frontend/category/all.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/{slug}", name="_single")
     */
    public function categorySingle()
    {

        $primary = false;

        if ($primary) {
            return $this->render('frontend/category/primary.html.twig', [
                'controller_name' => 'CategoryController',
                'category' => '',
            ]);
        } else {
            return $this->render('frontend/category/secondary.html.twig', [
                'controller_name' => 'CategoryController',
                'category' => '',
            ]);
        }
    }
}
