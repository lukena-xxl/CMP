<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Admin\Category\CategoryType;
use App\Form\Admin\Common\SortableType;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CategoryController
 * @package App\Controller\Admin
 * @Route("/admin/category", name="admin_category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param CategoryRepository $categoryRepository
     * @return Response
     * @throws MappingException
     * @throws ORMException
     */
    public function categoryAll(CategoryRepository $categoryRepository)
    {
        return $this->render('admin/category/all.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categoryRepository->getCategoryTree(),
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param Category $category
     * @return Response
     */
    public function categorySingle(Category $category)
    {
        return $this->render('admin/category/single.html.twig', [
            'controller_name' => 'CategoryController',
            'category' => $category,
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function categoryAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(CategoryType::class, null, [
            'action' => $this->generateUrl('admin_category_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'category_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $category->setPosition(0);

            $entityManager->persist($category);
            $entityManager->flush();

            $message = $translator->trans('Категория успешно добавлена');
            $this->addFlash('success', $message);

            $nextAction = $form->get('submitAndAdd')->isClicked()
                ? 'admin_category_add'
                : 'admin_category_all';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/category/add.html.twig', [
            'controller_name' => 'CategoryController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление категории'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param Category $category
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function categoryEdit(Category $category, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(CategoryType::class, $category, [
            'action' => $this->generateUrl('admin_category_edit', ['id' => $category->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            $message = $translator->trans('Категория успешно изменена');
            $this->addFlash('success', $message);

            if ($form->get('submitAndAdd')->isClicked()) {
                return $this->redirectToRoute('admin_category_add');
            } else {
                return $this->redirectToRoute('admin_category_single', ['id' => $category->getId()]);
            }
        }

        return $this->render('admin/category/add.html.twig', [
            'controller_name' => 'CategoryController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование категории'),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function categoryDelete(Category $category, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($category);
        $entityManager->flush();

        $message = $translator->trans('Категория успешно удалена');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_category_all');
    }

    /**
     * @Route("/sort/{id}", name="_sort", requirements={"id"="\d+"})
     * @param Request $request
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function categorySort(Request $request, Category $category, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(SortableType::class, null, [
            'action' => $this->generateUrl('admin_category_sort', ['id' => $category->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty($data['sorted_data'])) {
                $arrayId = explode(',', $data['sorted_data']);
                foreach ($arrayId as $position=>$id) {
                    if ($category->getId() == $id) {
                        $category->setPosition($position);

                        $entityManager->persist($category);
                        $entityManager->flush();

                        break;
                    }
                }

                $this->addFlash('success', $translator->trans('Категории успешно отсортированы'));
            } else {
                $this->addFlash('warning', $translator->trans('Ничего не изменено'));
            }
        }

        $parentCategory = $category->getParentCategory();

        if ($parentCategory) {
            $value = $parentCategory->getId();
        } else {
            $value =  null;
        }

        $categories = $entityManager->getRepository(Category::class)->findBy(['parent_category' => $value], ['position' => 'ASC']);

        return $this->render('admin/category/sort.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
            'current' => $category,
            'form_sort' => $form->createView(),
        ]);
    }
}
