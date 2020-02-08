<?php

namespace App\Controller\Admin;

use App\Entity\ArticleTag;
use App\Form\Admin\ArticleTag\ArticleTagType;
use App\Form\Admin\Common\SortableType;
use App\Repository\ArticleTagRepository;
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
 * Class ArticleTagController
 * @package App\Controller\Admin
 * @Route("/admin/article/tag", name="admin_article_tag")
 */
class ArticleTagController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param ArticleTagRepository $articleTagRepository
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function articleTagAll(ArticleTagRepository $articleTagRepository, TranslationRecipient $translationRecipient)
    {
        $tags = [];
        $tagsAll = $articleTagRepository->findBy([], ['position' => 'ASC']);
        foreach ($tagsAll as $tag) {
            $tags[] = $translationRecipient->getTranslatedEntity($tag);
        }

        return $this->render('admin/article_tag/all.html.twig', [
            'controller_name' => 'ArticleTagController',
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param ArticleTag $articleTag
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function articleTagSingle(ArticleTag $articleTag, TranslationRecipient $translationRecipient)
    {
        $translation = $translationRecipient->getTranslation($articleTag);

        return $this->render('admin/article_tag/single.html.twig', [
            'controller_name' => 'ArticleTagController',
            'tag' => $articleTag,
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
    public function articleTagAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ArticleTagType::class, null, [
            'action' => $this->generateUrl('admin_article_tag_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'article_tag_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article_tag = $form->getData();

            $arrData = $request->request->get('article_tag');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($article_tag, 'name', 'uk', $arrData['translation_name'])
                ->translate($article_tag, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($article_tag);
            $entityManager->flush();

            $message = $translator->trans('Тег успешно добавлен');
            $this->addFlash('success', $message);

            $nextAction = $form->get('submitAndAdd')->isClicked()
                ? 'admin_article_tag_add'
                : 'admin_article_tag_all';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/article_tag/add.html.twig', [
            'controller_name' => 'ArticleTagController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление тега'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param ArticleTag $articleTag
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function articleTagEdit(ArticleTag $articleTag, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(ArticleTagType::class, $articleTag, [
            'action' => $this->generateUrl('admin_article_tag_edit', ['id' => $articleTag->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'article_tag_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrData = $request->request->get('article_tag');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($articleTag, 'name', 'uk', $arrData['translation_name'])
                ->translate($articleTag, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($articleTag);
            $entityManager->flush();

            $message = $translator->trans('Тег успешно изменен');
            $this->addFlash('success', $message);

            if ($form->get('submitAndAdd')->isClicked()) {
                return $this->redirectToRoute('admin_article_tag_add');
            } else {
                return $this->redirectToRoute('admin_article_tag_single', ['id' => $articleTag->getId()]);
            }
        }

        return $this->render('admin/article_tag/add.html.twig', [
            'controller_name' => 'ArticleTagController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование тега'),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param ArticleTag $articleTag
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function articleTagDelete(ArticleTag $articleTag, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($articleTag);
        $entityManager->flush();

        $message = $translator->trans('Тег успешно удален');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_article_tag_all');
    }

    /**
     * @Route("/sort/{id}", name="_sort", requirements={"id"="\d+"})
     * @param Request $request
     * @param ArticleTag $articleTag
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param TranslationRecipient $translationRecipient
     * @return Response
     */
    public function articleTagSort(Request $request, ArticleTag $articleTag, EntityManagerInterface $entityManager, TranslatorInterface $translator, TranslationRecipient $translationRecipient)
    {
        $form = $this->createForm(SortableType::class, null, [
            'action' => $this->generateUrl('admin_article_tag_sort', ['id' => $articleTag->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty($data['sorted_data'])) {
                $arrayId = explode(',', $data['sorted_data']);
                foreach ($arrayId as $position=>$id) {
                    if ($articleTag->getId() == $id) {
                        $articleTag->setPosition($position);

                        $entityManager->persist($articleTag);
                        $entityManager->flush();

                        break;
                    }
                }

                $this->addFlash('success', $translator->trans('Теги успешно отсортированы'));
            } else {
                $this->addFlash('warning', $translator->trans('Ничего не изменено'));
            }
        }

        $tags = $entityManager->getRepository(ArticleTag::class)->findBy([], ['position' => 'ASC']);

        // КОРЯВО (нужно исправить)
        if ($tags) {
            foreach ($tags as $tag) {
                $translationRecipient->getTranslatedEntity($tag);
            }
        }

        return $this->render('admin/article_tag/sort.html.twig', [
            'controller_name' => 'ArticleTagController',
            'tags' => $tags,
            'current' => $articleTag,
            'form_sort' => $form->createView(),
        ]);
    }
}
