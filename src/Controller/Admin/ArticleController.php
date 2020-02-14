<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\Admin\Article\ArticleType;
use App\Form\Admin\Common\SortableType;
use App\Repository\ArticleRepository;
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
 * Class ArticleController
 * @package App\Controller\Admin
 * @Route("/admin/article", name="admin_article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param ArticleRepository $articleRepository
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function articleAll(ArticleRepository $articleRepository, TranslationRecipient $translationRecipient)
    {
        $articles = [];
        $articlesAll = $articleRepository->findBy([], ['position' => 'ASC']);
        foreach ($articlesAll as $article) {
            $articles[] = $translationRecipient->getTranslatedEntity($article);
        }

        return $this->render('admin/article/all.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param Article $article
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function articleSingle(Article $article, TranslationRecipient $translationRecipient)
    {
        $translation = $translationRecipient->getTranslation($article);

        return $this->render('admin/article/single.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
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
    public function articleAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ArticleType::class, null, [
            'action' => $this->generateUrl('admin_article_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'article_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $arrData = $request->request->get('article');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($article, 'name', 'uk', $arrData['translation_name'])
                ->translate($article, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($article);
            $entityManager->flush();

            $message = $translator->trans('Публикация успешно добавлена');
            $this->addFlash('success', $message);

            $nextAction = $form->get('submitAndAdd')->isClicked()
                ? 'admin_article_add'
                : 'admin_article_all';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/article/add.html.twig', [
            'controller_name' => 'ArticleController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление публикации'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param Article $article
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function articleEdit(Article $article, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('admin_article_edit', ['id' => $article->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'article_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrData = $request->request->get('article');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($article, 'name', 'uk', $arrData['translation_name'])
                ->translate($article, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($article);
            $entityManager->flush();

            $message = $translator->trans('Публикация успешно изменена');
            $this->addFlash('success', $message);

            if ($form->get('submitAndAdd')->isClicked()) {
                return $this->redirectToRoute('admin_article_add');
            } else {
                return $this->redirectToRoute('admin_article_single', ['id' => $article->getId()]);
            }
        }

        return $this->render('admin/article/add.html.twig', [
            'controller_name' => 'ArticleController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование публикации'),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param Article $article
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function articleDelete(Article $article, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($article);
        $entityManager->flush();

        $message = $translator->trans('Публикация успешно удалена');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_article_all');
    }

    /**
     * @Route("/sort/{id}", name="_sort", requirements={"id"="\d+"})
     * @param Request $request
     * @param Article $article
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function articleTagSort(Request $request, Article $article, EntityManagerInterface $entityManager, TranslatorInterface $translator, TranslationRecipient $translationRecipient)
    {
        $form = $this->createForm(SortableType::class, null, [
            'action' => $this->generateUrl('admin_article_sort', ['id' => $article->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty($data['sorted_data'])) {
                $arrayId = explode(',', $data['sorted_data']);
                foreach ($arrayId as $position=>$id) {
                    if ($article->getId() == $id) {
                        $article->setPosition($position);

                        $entityManager->persist($article);
                        $entityManager->flush();

                        break;
                    }
                }

                $this->addFlash('success', $translator->trans('Публикации успешно отсортированы'));
            } else {
                $this->addFlash('warning', $translator->trans('Ничего не изменено'));
            }
        }

        $articles = $entityManager->getRepository(Article::class)->findBy(['article_category' => $article->getArticleCategory()->getId()], ['position' => 'ASC']);

        // КОРЯВО (нужно исправить)
        if ($articles) {
            foreach ($articles as $art) {
                $translationRecipient->getTranslatedEntity($art);
            }
        }

        return $this->render('admin/article/sort.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
            'current' => $article,
            'form_sort' => $form->createView(),
        ]);
    }
}
