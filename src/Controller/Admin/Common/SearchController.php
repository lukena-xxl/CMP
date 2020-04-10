<?php

namespace App\Controller\Admin\Common;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller\Admin\Common
 * @Route("/admin/common/search", name="admin_common_search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/form", name="_form", methods={"GET"})
     * @return Response
     */
    public function adminSearchForm()
    {
        return $this->render('common/search_form.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    /**
     * @Route("/product/{text}", name="_product", methods={"GET"})
     * @param $text
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function adminSearchProduct($text, ProductRepository $productRepository)
    {
        $data['txt'] = urldecode($text);

        $data['res'] = $this->renderView('admin/product/list_products_found.html.twig', [
                'products' => $productRepository->searchProducts($data['txt'], $this->getUser()),
            ]);

        return new JsonResponse($data);
    }
}
