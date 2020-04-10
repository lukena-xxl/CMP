<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\ProductItem;
use App\Form\Admin\Product\ProductType;
use App\Repository\ProductRepository;
use App\Services\ImageUpload;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function productAll(ProductRepository $productRepository)
    {
        $user = $this->getUser();

        return $this->render('admin/product/all.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $productRepository->adminProductsList($user),
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param Product $product
     * @param ProductRepository $productRepository
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function productSingle(Product $product, ProductRepository $productRepository, TranslatorInterface $translator)
    {
        if ($this->getUser() === $product->getUser() || $this->isGranted('ROLE_SUPERADMIN')) {
            $productFilters = [];
            $filters = $productRepository->findProductFilters($product->getId());
            foreach ($filters as $filter) {
                $productFilters[] = [
                    'filter' => $filter,
                    'elements' => $productRepository->findProductFilterElements($filter['id'], $product->getId())
                ];
            }

            return $this->render('admin/product/single.html.twig', [
                'controller_name' => 'ProductController',
                'product' => $product,
                'filters' => $productFilters,
            ]);
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param ParameterBagInterface $parameterBag
     * @param ImageUpload $imageUpload
     * @return RedirectResponse|Response
     */
    public function productAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, ParameterBagInterface $parameterBag, ImageUpload $imageUpload)
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
            $user = $this->getUser();

            $product = $form->getData();
            $product->setUser($user);

            $arrData = $request->request->get('product');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($product, 'name', 'uk', $arrData['translation_name'])
                    ->translate($product, 'description', 'uk', $arrData['translation_description']);

            /**
             * @var ProductItem $item
             */
            $items = $form->get('items')->getData();
            $image_item_dir = $parameterBag->get('image_item_dir');

            foreach ($items as $key=>$item) {
                $repoTranslation->translate($item, 'name', 'uk', $arrData['items'][$key]['translation_name']);

                $image_item = $item->getImg();

                if (!empty($image_item)) {
                    $name_item = $item->getName();

                    if (!empty($name_item)) {
                        $path_name = $name_item;
                    } else {
                        $path_name = $product->getName();
                    }

                    $name_image_item = $imageUpload->base64ImageUpload($image_item, $image_item_dir, $path_name);

                    if (!$name_image_item) {
                        $name_image_item = '';
                    }

                    $item->setImg($name_image_item);
                }
            }

            /**
             * @var ProductImage $image
             */
            $images = $form->get('images')->getData();
            $image_product_dir = $parameterBag->get('image_product_dir');
            $image_product_subdirs = $parameterBag->get('image_product_subdirs');

            foreach ($images as $image) {
                $name_image = $imageUpload->base64ImageUpload($image->getName(), $image_product_dir, $product->getName(), $image_product_subdirs);

                if ($name_image) {
                    $image->setName($name_image);

                    if ($image->getPosition() == 0) {
                        $image->setIsMain(true);
                    }
                } else {
                    unset($image);
                }
            }

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
     * @param Product $product
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param ParameterBagInterface $parameterBag
     * @param ImageUpload $imageUpload
     * @return RedirectResponse|Response
     */
    public function productEdit(Product $product, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, ParameterBagInterface $parameterBag, ImageUpload $imageUpload)
    {
        $user = $this->getUser();

        if ($user === $product->getUser() || $this->isGranted('ROLE_SUPERADMIN')) {

            $form = $this->createForm(ProductType::class, $product, [
                'action' => $this->generateUrl('admin_product_edit', ['id' => $product->getId()]),
                'method' => 'post',
                'attr' => [
                    'id' => 'product_form',
                ],
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $image_product_dir = $parameterBag->get('image_product_dir');

                $arrData = $request->request->get('product');
                $repoTranslation = $entityManager->getRepository(Translation::class);
                $repoTranslation->translate($product, 'name', 'uk', $arrData['translation_name'])
                    ->translate($product, 'description', 'uk', $arrData['translation_description']);

                $pattern_for_image = '/^data:image\/\w+;base64,/i';

                /**
                 * @var ProductItem $item
                 */
                $items = $form->get('items')->getData();
                $image_item_dir = $parameterBag->get('image_item_dir');

                foreach ($items as $key=>$item) {
                    $repoTranslation->translate($item, 'name', 'uk', $arrData['items'][$key]['translation_name']);
                    $image_item = $item->getImg();
                    if (!empty($image_item)) {
                        if (preg_match($pattern_for_image, $image_item)) {
                            $name_item = $item->getName();

                            if (!empty($name_item)) {
                                $path_name = $name_item;
                            } else {
                                $path_name = $product->getName();
                            }

                            $name_image_item = $imageUpload->base64ImageUpload($image_item, $image_item_dir, $path_name);

                            if (!$name_image_item) {
                                $name_image_item = '';
                            }

                            $item->setImg($name_image_item);
                        }
                    }
                }

                /**
                 * @var ProductImage $image
                 */
                $images = $form->get('images')->getData();
                $image_product_subdirs = $parameterBag->get('image_product_subdirs');

                foreach ($images as $image) {
                    if (preg_match($pattern_for_image, $image->getName())) {
                        $name_image = $imageUpload->base64ImageUpload($image->getName(), $image_product_dir, $product->getName(), $image_product_subdirs);
                        if ($name_image) {
                            $image->setName($name_image);
                        } else {
                            unset($image);
                            continue;
                        }
                    }

                    $is_main = false;
                    if ($image->getPosition() == 0) {
                        $is_main = true;
                    }

                    $image->setIsMain($is_main);
                }

                $entityManager->persist($product);
                $entityManager->flush();

                $message = $translator->trans('Продукт успешно изменен');
                $this->addFlash('success', $message);

                if ($form->get('submitAndAdd')->isClicked()) {
                    return $this->redirectToRoute('admin_product_add');
                } else {
                    return $this->redirectToRoute('admin_product_single', ['id' => $product->getId()]);
                }
            }

            return $this->render('admin/product/add.html.twig', [
                'controller_name' => 'ProductController',
                'form_add' => $form->createView(),
                'title' => $translator->trans('Редактирование продукта'),
            ]);
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param Product $product
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function productDelete(Product $product, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $user = $this->getUser();

        if ($user === $product->getUser() || $this->isGranted('ROLE_SUPERADMIN')) {
            $entityManager->remove($product);
            $entityManager->flush();

            $message = $translator->trans('Продукт успешно удален');

            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_product_all');
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }
}
