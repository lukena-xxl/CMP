<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller\Frontend
 * @Route("/product", name="frontend_product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="_single")
     */
    public function productSingle()
    {
        return $this->render('frontend/product/single.html.twig', [
            'controller_name' => 'ProductController',
            'product' => '',
        ]);
    }
}
