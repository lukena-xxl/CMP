<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package App\Controller\Admin
 * @Route("/admin", name="admin")
 */
class MainController extends AbstractController
{
    /**
     * @Route("", name="_main")
     */
    public function index()
    {
        return $this->render('admin/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
