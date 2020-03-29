<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\ProductItem;
use App\Form\Admin\Product\ProductType;
use App\Repository\ProductRepository;
use App\Services\Common\SlugCreator;
use App\Services\Common\TranslationRecipient;
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
        return $this->render('admin/product/all.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $productRepository->adminProductsList(),
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param Product $product
     * @param TranslationRecipient $translationRecipient
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function productSingle(Product $product, TranslationRecipient $translationRecipient, TranslatorInterface $translator)
    {
        if ($this->getUser() === $product->getUser() || $this->isGranted('ROLE_SUPERADMIN')) {
            $translation = $translationRecipient->getTranslation($product);

            return $this->render('admin/product/single.html.twig', [
                'controller_name' => 'ProductController',
                'product' => $product,
                'translation' => $translation,
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
     * @param SlugCreator $slugCreator
     * @param ParameterBagInterface $parameterBag
     * @return RedirectResponse|Response
     */
    public function productAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, SlugCreator $slugCreator, ParameterBagInterface $parameterBag)
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
            $image_product_dir = $parameterBag->get('image_product_dir');
            $product->setUser($user);

            $arrData = $request->request->get('product');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($product, 'name', 'uk', $arrData['translation_name'])
                    ->translate($product, 'description', 'uk', $arrData['translation_description']);

            /**
             * @var ProductItem $item
             */
            $items = $form->get('items')->getData();
            $x = 0;

            foreach ($items as $key=>$item) {
                $repoTranslation->translate($item, 'name', 'uk', $arrData['items'][$key]['translation_name']);

                $img = $item->getImg();
                if (!empty($img)) {
                    $data = base64_decode(preg_replace('/^data:image\/\w+;base64,/i', '', $img));

                    $path_name = $product->getName();
                    $name_item = $item->getName();
                    if (!empty($name_item)) {
                        $path_name .= " " . $name_item;
                    }

                    $name_img = $slugCreator->createSlug($path_name) . "-" . $x . "-" . time() . ".jpg";

                    $im = imagecreatefromstring($data);
                    imageinterlace($im, true);
                    $out = $image_product_dir . "item/" . $name_img;
                    imagejpeg($im, $out);
                    imagedestroy($im);

                    $item->setImg($name_img);

                    $x++;
                }
            }

            /**
             * @var ProductImage $image
             */
            $images = $form->get('images')->getData();
            $x = 0;
            $arrDir = [800, 150];

            foreach ($images as $image) {
                $data = base64_decode(preg_replace('/^data:image\/\w+;base64,/i', '', $image->getName()));
                $name_img = $slugCreator->createSlug($product->getName()) . "-" . $x . "-" . time() . ".jpg";

                $im = imagecreatefromstring($data);
                $W = imagesx($im);
                $H = imagesy($im);

                foreach ($arrDir as $dir) {
                    $Ws = $dir;
                    $Hs = ($dir * $H / $W);
                    $ims = imagecreatetruecolor($Ws, $Hs);
                    imagecopyresampled($ims, $im, 0, 0, 0, 0, $Ws, $Hs, $W, $H);
                    imageinterlace($ims, true);

                    $out = $image_product_dir . $dir . "/" . $name_img;

                    imagejpeg($ims, $out);
                    imagedestroy($ims);
                }

                imageinterlace($im, true);
                $out = $image_product_dir . $name_img;
                imagejpeg($im, $out);
                imagedestroy($im);

                $image->setName($name_img);

                if ($x == 0) {
                    $image->setIsMain(true);
                }

                $x++;
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
     * @param SlugCreator $slugCreator
     * @param ParameterBagInterface $parameterBag
     * @return RedirectResponse|Response
     */
    public function productEdit(Product $product, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, SlugCreator $slugCreator, ParameterBagInterface $parameterBag)
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

                /**
                 * @var ProductItem $item
                 */
                $items = $form->get('items')->getData();
                $x = 0;

                foreach ($items as $key=>$item) {
                    $repoTranslation->translate($item, 'name', 'uk', $arrData['items'][$key]['translation_name']);
                    $img = $item->getImg();
                    if (!empty($img)) {
                        if (preg_match('/^data:image\/\w+;base64,/i', $img)) {
                            $data = base64_decode(preg_replace('/^data:image\/\w+;base64,/i', '', $img));

                            $path_name = $product->getName();
                            $name_item = $item->getName();
                            if (!empty($name_item)) {
                                $path_name .= " " . $name_item;
                            }

                            $name_img = $slugCreator->createSlug($path_name) . "-" . $x . "-" . time() . ".jpg";

                            $im = imagecreatefromstring($data);
                            imageinterlace($im, true);
                            $out = $image_product_dir . "item/" . $name_img;
                            imagejpeg($im, $out);
                            imagedestroy($im);

                            $item->setImg($name_img);
                        }
                    }

                    $item->setPosition($x);

                    $x++;
                }

                /**
                 * @var ProductImage $image
                 */
                $images = $form->get('images')->getData();
                $x = 0;
                $arrDir = [800, 150];

                foreach ($images as $image) {
                    $img = $image->getName();
                    if (!empty($img)) {
                        if (preg_match('/^data:image\/\w+;base64,/i', $img)) {
                            $data = base64_decode(preg_replace('/^data:image\/\w+;base64,/i', '', $img));
                            $name_img = $slugCreator->createSlug($product->getName()) . "-" . $x . "-" . time() . ".jpg";

                            $im = imagecreatefromstring($data);
                            $W = imagesx($im);
                            $H = imagesy($im);

                            foreach ($arrDir as $dir) {
                                $Ws = $dir;
                                $Hs = ($dir * $H / $W);
                                $ims = imagecreatetruecolor($Ws, $Hs);
                                imagecopyresampled($ims, $im, 0, 0, 0, 0, $Ws, $Hs, $W, $H);
                                imageinterlace($ims, true);

                                $out = $image_product_dir . $dir . "/" . $name_img;

                                imagejpeg($ims, $out);
                                imagedestroy($ims);
                            }

                            imageinterlace($im, true);
                            $out = $image_product_dir . $name_img;
                            imagejpeg($im, $out);
                            imagedestroy($im);

                            $image->setName($name_img);
                        }

                        if ($x == 0) {
                            $image->setIsMain(true);
                            $image->setPosition($x);
                        }

                        //$image->setPosition($x);
                        $x++;
                    }
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
     */
    public function productDelete()
    {
    }
}
