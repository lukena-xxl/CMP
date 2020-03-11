<?php

namespace App\Controller\Admin;

use App\Form\Admin\Product\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ProductController
 * @package App\Controller\Admin
 * @Route("/admin/product", name="admin_product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("", name="_all")
     */
    public function productAll()
    {
        return $this->render('admin/product/all.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     */
    public function productSingle()
    {

    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function productAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ProductType::class, null, [
            'action' => $this->generateUrl('admin_product_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'product_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $arrData = $request->request->get('product');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($product, 'name', 'uk', $arrData['translation_name'])
                ->translate($product, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($product);
            $entityManager->flush();

            $message = $translator->trans('Продукт успешно добавлен');
            $this->addFlash('success', $message);

            $nextAction = $form->get('submitAndAdd')->isClicked()
                ? 'admin_product_add'
                : 'admin_product_all';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/product/add.html.twig', [
            'controller_name' => 'ProductController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление продукта'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     */
    public function productEdit()
    {

    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     */
    public function productDelete()
    {

    }
}
