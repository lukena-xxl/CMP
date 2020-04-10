<?php

namespace App\Controller\Admin;

use App\Entity\ProductCaption;
use App\Form\Admin\Common\SortableType;
use App\Form\Admin\ProductCaption\ProductCaptionType;
use App\Repository\ProductCaptionRepository;
use App\Services\Common\TranslationRecipient;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ProductCaptionController
 * @package App\Controller\Admin
 * @Route("/admin/product/caption", name="admin_product_caption")
 */
class ProductCaptionController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param ProductCaptionRepository $productCaptionRepository
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function productCaptionAll(ProductCaptionRepository $productCaptionRepository, TranslationRecipient $translationRecipient)
    {
        $captions = [];
        $captionsAll = $productCaptionRepository->findBy([], ['position' => 'ASC']);
        foreach ($captionsAll as $caption) {
            $captions[] = $translationRecipient->getTranslatedEntity($caption);
        }

        return $this->render('admin/product_caption/all.html.twig', [
            'controller_name' => 'ProductCaptionController',
            'captions' => $captions,
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param ProductCaption $caption
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function productCaptionSingle(ProductCaption $caption, TranslationRecipient $translationRecipient)
    {
        $translation = $translationRecipient->getTranslation($caption);

        return $this->render('admin/product_caption/single.html.twig', [
            'controller_name' => 'ProductCaptionController',
            'caption' => $caption,
            'translation' => $translation,
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function productCaptionAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ProductCaptionType::class, null, [
            'action' => $this->generateUrl('admin_product_caption_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'product_caption_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caption = $form->getData();

            $arrData = $request->request->get('product_caption');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($caption, 'name', 'uk', $arrData['translation_name']);

            $entityManager->persist($caption);
            $entityManager->flush();

            $message = $translator->trans('Подпись успешно добавлена');
            $this->addFlash('success', $message);

            $nextAction = $form->get('submitAndAdd')->isClicked()
                ? 'admin_product_caption_add'
                : 'admin_product_caption_all';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/product_caption/add.html.twig', [
            'controller_name' => 'ProductCaptionController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление подписи'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param ProductCaption $caption
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function productCaptionEdit(ProductCaption $caption, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ProductCaptionType::class, $caption, [
            'action' => $this->generateUrl('admin_product_caption_edit', ['id' => $caption->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'product_caption_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrData = $request->request->get('product_caption');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($caption, 'name', 'uk', $arrData['translation_name']);

            $entityManager->persist($caption);
            $entityManager->flush();

            $message = $translator->trans('Подпись успешно изменена');
            $this->addFlash('success', $message);

            if ($form->get('submitAndAdd')->isClicked()) {
                return $this->redirectToRoute('admin_product_caption_add');
            } else {
                return $this->redirectToRoute('admin_product_caption_single', ['id' => $caption->getId()]);
            }
        }

        return $this->render('admin/product_caption/add.html.twig', [
            'controller_name' => 'ProductCaptionController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование подписи'),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param ProductCaption $caption
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function productCaptionDelete(ProductCaption $caption, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($caption);
        $entityManager->flush();

        $message = $translator->trans('Подпись успешно удалена');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_product_caption_all');
    }

    /**
     * @Route("/sort/{id}", name="_sort", requirements={"id"="\d+"})
     * @param Request $request
     * @param ProductCaption $caption
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function productCaptionSort(Request $request, ProductCaption $caption, EntityManagerInterface $entityManager, TranslatorInterface $translator, TranslationRecipient $translationRecipient)
    {
        $form = $this->createForm(SortableType::class, null, [
            'action' => $this->generateUrl('admin_product_caption_sort', ['id' => $caption->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty($data['sorted_data'])) {
                $arrayId = explode(',', $data['sorted_data']);
                foreach ($arrayId as $position=>$id) {
                    if ($caption->getId() == $id) {
                        $caption->setPosition($position);

                        $entityManager->persist($caption);
                        $entityManager->flush();

                        break;
                    }
                }

                $this->addFlash('success', $translator->trans('Подписи успешно отсортированы'));
            } else {
                $this->addFlash('warning', $translator->trans('Ничего не изменено'));
            }
        }

        $captions = $entityManager->getRepository(ProductCaption::class)->findBy([], ['position' => 'ASC']);

        // КОРЯВО (нужно исправить)
        if ($captions) {
            foreach ($captions as $cap) {
                $translationRecipient->getTranslatedEntity($cap);
            }
        }

        return $this->render('admin/product_caption/sort.html.twig', [
            'controller_name' => 'ProductCaptionController',
            'captions' => $captions,
            'current' => $caption,
            'form_sort' => $form->createView(),
        ]);
    }
}
