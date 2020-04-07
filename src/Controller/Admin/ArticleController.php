<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\Admin\Article\ArticleType;
use App\Form\Admin\Common\SortableType;
use App\Repository\ArticleRepository;
use App\Services\ImageUpload;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ArticleController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_SUPERADMIN", message="Access denied for you!")
 * @Route("/admin/article", name="admin_article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function articleAll(ArticleRepository $articleRepository)
    {
        return $this->render('admin/article/all.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articleRepository->findBy([], ['position' => 'ASC']),
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param Article $article
     * @return Response
     */
    public function articleSingle(Article $article)
    {
        return $this->render('admin/article/single.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
        ]);
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
    public function articleAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, ParameterBagInterface $parameterBag, ImageUpload $imageUpload)
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

            $image_article_dir = $parameterBag->get('image_article_dir');
            $name_image = $imageUpload->base64ImageUpload($article->getImage(), $image_article_dir, $article->getName());

            if (!$name_image) {
                $name_image = '';
            }

            $article->setImage($name_image);

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
     * @param ParameterBagInterface $parameterBag
     * @param ImageUpload $imageUpload
     * @return RedirectResponse|Response
     */
    public function articleEdit(Article $article, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, ParameterBagInterface $parameterBag, ImageUpload $imageUpload)
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

            $pattern_for_image = '/^data:image\/\w+;base64,/i';

            $image = $article->getImage();
            if (!empty($image)) {
                if (preg_match($pattern_for_image, $image)) {
                    $image_article_dir = $parameterBag->get('image_article_dir');
                    $name_image = $imageUpload->base64ImageUpload($image, $image_article_dir, $article->getName());

                    if (!$name_image) {
                        $name_image = '';
                    }

                    $article->setImage($name_image);
                }
            }

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
     * @return Response
     */
    public function articleTagSort(Request $request, Article $article, EntityManagerInterface $entityManager, TranslatorInterface $translator)
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


        return $this->render('admin/article/sort.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
            'current' => $article,
            'form_sort' => $form->createView(),
        ]);
    }
}
