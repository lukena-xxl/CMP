<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package App\Controller\Fronted
 * @Route("/", name="frontend")
 */
class MainController extends AbstractController
{
    /**
     * @Route("", name="_main")
     */
    public function index()
    {
        return $this->render('frontend/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
