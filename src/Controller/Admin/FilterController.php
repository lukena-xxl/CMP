<?php

namespace App\Controller\Admin;

use App\Entity\Filter;
use App\Form\Admin\Common\SortableType;
use App\Form\Admin\Filter\FilterType;
use App\Repository\FilterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class FilterController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_SUPERADMIN", message="Access denied for you!")
 * @Route("/admin/filter", name="admin_filter")
 */
class FilterController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param FilterRepository $filterRepository
     * @return Response
     */
    public function filterAll(FilterRepository $filterRepository)
    {
        return $this->render('admin/filter/all.html.twig', [
            'controller_name' => 'FilterController',
            'filters' => $filterRepository->findBy([], ['position' => 'ASC']),
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param Filter $filter
     * @return Response
     */
    public function filterSingle(Filter $filter)
    {
        return $this->render('admin/filter/single.html.twig', [
            'controller_name' => 'FilterController',
            'filter' => $filter,
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function filterAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(FilterType::class, null, [
            'action' => $this->generateUrl('admin_filter_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'filter_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();

            $arrData = $request->request->get('filter');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($filter, 'name', 'uk', $arrData['translation_name']);

            $entityManager->persist($filter);
            $entityManager->flush();

            $message = $translator->trans('Фильтр успешно добавлен');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_filter_all');
        }

        return $this->render('admin/filter/add.html.twig', [
            'controller_name' => 'FilterController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление фильтра'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param Filter $filter
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function filterEdit(Filter $filter, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(FilterType::class, $filter, [
            'action' => $this->generateUrl('admin_filter_edit', ['id' => $filter->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'filter_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrData = $request->request->get('filter');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($filter, 'name', 'uk', $arrData['translation_name']);

            $entityManager->persist($filter);
            $entityManager->flush();

            $message = $translator->trans('Фильтр успешно изменен');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_filter_single', ['id' => $filter->getId()]);
        }

        return $this->render('admin/filter/add.html.twig', [
            'controller_name' => 'FilterController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование фильтра'),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param Filter $filter
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function filterDelete(Filter $filter, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($filter);
        $entityManager->flush();

        $message = $translator->trans('Фильтр успешно удален');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_filter_all');
    }

    /**
     * @Route("/sort/{id}", name="_sort", requirements={"id"="\d+"})
     * @param Request $request
     * @param Filter $filter
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function filterElementSort(Request $request, Filter $filter, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(SortableType::class, null, [
            'action' => $this->generateUrl('admin_filter_sort', ['id' => $filter->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty($data['sorted_data'])) {
                $arrayId = explode(',', $data['sorted_data']);
                foreach ($arrayId as $position=>$id) {
                    if ($filter->getId() == $id) {
                        $filter->setPosition($position);

                        $entityManager->persist($filter);
                        $entityManager->flush();

                        break;
                    }
                }

                $this->addFlash('success', $translator->trans('Фильтры успешно отсортированы'));
            } else {
                $this->addFlash('warning', $translator->trans('Ничего не изменено'));
            }
        }

        $filters = $entityManager->getRepository(Filter::class)->findBy([], ['position' => 'ASC']);

        return $this->render('admin/filter/sort.html.twig', [
            'controller_name' => 'FilterController',
            'filters' => $filters,
            'current' => $filter,
            'form_sort' => $form->createView(),
        ]);

    }
}
