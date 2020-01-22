<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_main")
     */
    public function index()
    {
        return $this->render('admin/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
