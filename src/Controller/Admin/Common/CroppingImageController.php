<?php

namespace App\Controller\Admin\Common;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CroppingImageController
 * @package App\Controller\Admin\Common
 * @Route("/admin/common", name="admin_common")
 */
class CroppingImageController extends AbstractController
{
    /**
     * @Route("/cropping/image", name="_cropping_image", methods={"GET"})
     * @return Response
     */
    public function croppingImage()
    {
        return $this->render('common/cropping_image.html.twig', [
            'controller_name' => 'CroppingImageController',
        ]);
    }
}
