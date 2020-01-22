<?php

namespace App\Controller\Fronted;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="fronted_main")
     */
    public function index()
    {
        return $this->render('fronted/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
