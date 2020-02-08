<?php

namespace App\Controller\Admin;

use App\Entity\ArticleCategory;
use App\Form\Admin\ArticleCategory\ArticleCategoryType;
use App\Form\Admin\Common\SortableType;
use App\Repository\ArticleCategoryRepository;
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
 * Class ArticleCategoryController
 * @package App\Controller\Admin
 * @Route("/admin/article/category", name="admin_article_category")
 */
class ArticleCategoryController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param ArticleCategoryRepository $articleCategoryRepository
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function articleCategoryAll(ArticleCategoryRepository $articleCategoryRepository, TranslationRecipient $translationRecipient)
    {
        $categories = [];
        $categoriesAll = $articleCategoryRepository->findBy([], ['position' => 'ASC']);
        foreach ($categoriesAll as $category) {
            $categories[] = $translationRecipient->getTranslatedEntity($category);
        }

        return $this->render('admin/article_category/all.html.twig', [
            'controller_name' => 'ArticleCategoryController',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param ArticleCategory $articleCategory
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function articleCategorySingle(ArticleCategory $articleCategory, TranslationRecipient $translationRecipient)
    {
        $translation = $translationRecipient->getTranslation($articleCategory);

        return $this->render('admin/article_category/single.html.twig', [
            'controller_name' => 'ArticleCategoryController',
            'category' => $articleCategory,
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
    public function articleCategoryAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ArticleCategoryType::class, null, [
            'action' => $this->generateUrl('admin_article_category_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'article_category_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article_category = $form->getData();

            $arrData = $request->request->get('article_category');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($article_category, 'name', 'uk', $arrData['translation_name'])
                ->translate($article_category, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($article_category);
            $entityManager->flush();

            $message = $translator->trans('Категория успешно добавлена');
            $this->addFlash('success', $message);

            $nextAction = $form->get('submitAndAdd')->isClicked()
                ? 'admin_article_category_add'
                : 'admin_article_category_all';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/article_category/add.html.twig', [
            'controller_name' => 'ArticleCategoryController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление категории'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param ArticleCategory $articleCategory
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function articleCategoryEdit(ArticleCategory $articleCategory, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ArticleCategoryType::class, $articleCategory, [
            'action' => $this->generateUrl('admin_article_category_edit', ['id' => $articleCategory->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'article_category_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrData = $request->request->get('article_category');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($articleCategory, 'name', 'uk', $arrData['translation_name'])
                ->translate($articleCategory, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($articleCategory);
            $entityManager->flush();

            $message = $translator->trans('Категория успешно изменена');
            $this->addFlash('success', $message);

            if ($form->get('submitAndAdd')->isClicked()) {
                return $this->redirectToRoute('admin_article_category_add');
            } else {
                return $this->redirectToRoute('admin_article_category_single', ['id' => $articleCategory->getId()]);
            }
        }

        return $this->render('admin/article_category/add.html.twig', [
            'controller_name' => 'ArticleCategoryController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование категории'),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param ArticleCategory $articleCategory
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function articleCategoryDelete(ArticleCategory $articleCategory, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($articleCategory);
        $entityManager->flush();

        $message = $translator->trans('Категория успешно удалена');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_article_category_all');
    }

    /**
     * @Route("/sort/{id}", name="_sort", requirements={"id"="\d+"})
     * @param Request $request
     * @param ArticleCategory $articleCategory
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function articleCategorySort(Request $request, ArticleCategory $articleCategory, EntityManagerInterface $entityManager, TranslatorInterface $translator, TranslationRecipient $translationRecipient)
    {
        $form = $this->createForm(SortableType::class, null, [
            'action' => $this->generateUrl('admin_article_category_sort', ['id' => $articleCategory->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty($data['sorted_data'])) {
                $arrayId = explode(',', $data['sorted_data']);
                foreach ($arrayId as $position=>$id) {
                    if ($articleCategory->getId() == $id) {
                        $articleCategory->setPosition($position);

                        $entityManager->persist($articleCategory);
                        $entityManager->flush();

                        break;
                    }
                }

                $this->addFlash('success', $translator->trans('Категории успешно отсортированы'));
            } else {
                $this->addFlash('warning', $translator->trans('Ничего не изменено'));
            }
        }

        $categories = $entityManager->getRepository(ArticleCategory::class)->findBy([], ['position' => 'ASC']);

        // КОРЯВО (нужно исправить)
        if ($categories) {
            foreach ($categories as $cat) {
                $translationRecipient->getTranslatedEntity($cat);
            }
        }

        return $this->render('admin/article_category/sort.html.twig', [
            'controller_name' => 'ArticleCategoryController',
            'categories' => $categories,
            'current' => $articleCategory,
            'form_sort' => $form->createView(),
        ]);
    }
}
