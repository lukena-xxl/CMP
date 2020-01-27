<?php

namespace App\Controller\Admin;

use App\Form\Admin\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
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
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("", name="_all")
     */
    public function categoryAll()
    {
        return $this->render('admin/category/all.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function categoryAdd(Request $request, EntityManagerInterface $entityManager)
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

            $entityManager->persist($category);
            $entityManager->flush();

            $message = $this->translator->trans('Категория успешно добавлена');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_category_all');
        }

        return $this->render('admin/category/add.html.twig', [
            'controller_name' => 'CategoryController',
            'form_add' => $form->createView(),
        ]);
    }
}
