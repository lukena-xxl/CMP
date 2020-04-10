<?php

namespace App\Controller\Admin;

use App\Entity\Filter;
use App\Entity\FilterElement;
use App\Form\Admin\Common\SortableType;
use App\Form\Admin\Filter\FilterElementType;
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
 * Class FilterElementController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_SUPERADMIN", message="Access denied for you!")
 * @Route("/admin/filter", name="admin_filter_element")
 */
class FilterElementController extends AbstractController
{
    /**
     * @Route("/{id}/element/add", name="_add", requirements={"id"="\d+"})
     * @param Request $request
     * @param Filter $filter
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function filterElementAdd(Request $request, Filter $filter, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(FilterElementType::class, null, [
            'action' => $this->generateUrl('admin_filter_element_add', ['id' => $filter->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'filter_element_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filterElement = $form->getData();

            $arrData = $request->request->get('filter_element');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($filterElement, 'name', 'uk', $arrData['translation_name']);

            $filterElement->setFilter($filter);

            $entityManager->persist($filterElement);
            $entityManager->flush();

            $message = $translator->trans('Элемент фильтра успешно добавлен');
            $this->addFlash('success', $message);

            if ($form->get('submitAndAdd')->isClicked()) {
                return $this->redirectToRoute('admin_filter_element_add', ['id' => $filter->getId()]);
            } else {
                return $this->redirectToRoute('admin_filter_single', ['id' => $filter->getId()]);
            }
        }

        return $this->render('admin/filter/element_add.html.twig', [
            'controller_name' => 'FilterElementController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление элемента фильтра'),
            'filter' => $filter,
        ]);
    }

    /**
     * @Route("/element/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param FilterElement $filterElement
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function filterElementEdit(FilterElement $filterElement, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(FilterElementType::class, $filterElement, [
            'action' => $this->generateUrl('admin_filter_element_edit', ['id' => $filterElement->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'filter_element_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrData = $request->request->get('filter_element');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($filterElement, 'name', 'uk', $arrData['translation_name']);

            $entityManager->persist($filterElement);
            $entityManager->flush();

            $message = $translator->trans('Элемент фильтра успешно изменен');
            $this->addFlash('success', $message);

            if ($form->get('submitAndAdd')->isClicked()) {
                return $this->redirectToRoute('admin_filter_element_add', ['id' => $filterElement->getFilter()->getId()]);
            } else {
                return $this->redirectToRoute('admin_filter_single', ['id' => $filterElement->getFilter()->getId()]);
            }
        }

        return $this->render('admin/filter/element_add.html.twig', [
            'controller_name' => 'FilterElementController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование элемента фильтра'),
            'filter' => $filterElement->getFilter(),
        ]);
    }

    /**
     * @Route("/element/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param FilterElement $filterElement
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function filterElementDelete(FilterElement $filterElement, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($filterElement);
        $entityManager->flush();

        $message = $translator->trans('Элемент фильтра успешно удален');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_filter_single', ['id' => $filterElement->getFilter()->getId()]);
    }

    /**
     * @Route("/element/sort/{id}", name="_sort", requirements={"id"="\d+"})
     * @param Request $request
     * @param FilterElement $filterElement
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function filterElementSort(Request $request, FilterElement $filterElement, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(SortableType::class, null, [
            'action' => $this->generateUrl('admin_filter_element_sort', ['id' => $filterElement->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty($data['sorted_data'])) {
                $arrayId = explode(',', $data['sorted_data']);
                foreach ($arrayId as $position=>$id) {
                    if ($filterElement->getId() == $id) {
                        $filterElement->setPosition($position);

                        $entityManager->persist($filterElement);
                        $entityManager->flush();

                        break;
                    }
                }

                $this->addFlash('success', $translator->trans('Элементы фильтра успешно отсортированы'));
            } else {
                $this->addFlash('warning', $translator->trans('Ничего не изменено'));
            }
        }

        $filterElements = $entityManager->getRepository(FilterElement::class)->findBy(['filter' => $filterElement->getFilter()->getId()], ['position' => 'ASC']);

        return $this->render('admin/filter/element_sort.html.twig', [
            'controller_name' => 'FilterElementController',
            'elements' => $filterElements,
            'current' => $filterElement,
            'form_sort' => $form->createView(),
        ]);

    }
}
