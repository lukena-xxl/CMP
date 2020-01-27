<?php

namespace App\Controller\Fronted;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller\Fronted
 * @Route("/product", name="fronted_product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("", name="_all")
     */
    public function productAll()
    {
        return $this->render('fronted/product/all.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/{slug}", name="_single")
     */
    public function productSingle()
    {
        return $this->render('fronted/product/single.html.twig', [
            'controller_name' => 'ProductController',
            'product' => '',
        ]);
    }
}
