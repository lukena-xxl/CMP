<?php

namespace App\Controller\Fronted;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package App\Controller\Fronted
 * @Route("/", name="fronted")
 */
class MainController extends AbstractController
{
    /**
     * @Route("", name="_main")
     */
    public function index()
    {
        return $this->render('fronted/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
