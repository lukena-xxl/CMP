<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ParameterController
 * @package App\Controller\Admin
 * @Route("/admin/parameter", name="admin_parameter")
 */
class ParameterController extends AbstractController
{
    /**
     * @Route("", name="_all")
     */
    public function parameterAll()
    {
        return $this->render('admin/parameter/all.html.twig', [
            'controller_name' => 'ParameterController',
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     */
    public function parameterSingle()
    {

    }

    /**
     * @Route("/add", name="_add")
     */
    public function parameterAdd()
    {

    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     */
    public function parameterEdit()
    {

    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     */
    public function parameterDelete()
    {

    }
}
