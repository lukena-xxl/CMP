<?php

namespace App\Controller\Admin;

use App\Entity\DeliveryMethod;
use App\Form\Admin\Common\SortableType;
use App\Form\Admin\DeliveryMethod\DeliveryMethodType;
use App\Repository\DeliveryMethodRepository;
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
 * Class DeliveryMethodController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_SUPERADMIN", message="Access denied for you!")
 * @Route("/admin/delivery", name="admin_delivery")
 */
class DeliveryMethodController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param DeliveryMethodRepository $deliveryMethodRepository
     * @return Response
     */
    public function deliveryAll(DeliveryMethodRepository $deliveryMethodRepository)
    {
        return $this->render('admin/delivery_method/all.html.twig', [
            'controller_name' => 'DeliveryMethodController',
            'deliveries' => $deliveryMethodRepository->findBy([], ['position' => 'ASC']),
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param DeliveryMethod $deliveryMethod
     * @return Response
     */
    public function deliverySingle(DeliveryMethod $deliveryMethod)
    {
        return $this->render('admin/delivery_method/single.html.twig', [
            'controller_name' => 'DeliveryMethodController',
            'delivery' => $deliveryMethod,
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function deliveryAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(DeliveryMethodType::class, null, [
            'action' => $this->generateUrl('admin_delivery_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'delivery_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $delivery = $form->getData();

            $arrData = $request->request->get('delivery_method');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($delivery, 'name', 'uk', $arrData['translation_name'])
                ->translate($delivery, 'short_description', 'uk', $arrData['translation_short_description'])
                ->translate($delivery, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($delivery);
            $entityManager->flush();

            $message = $translator->trans('Способ доставки успешно добавлен');
            $this->addFlash('success', $message);

            $nextAction = $form->get('submitAndAdd')->isClicked()
                ? 'admin_delivery_add'
                : 'admin_delivery_all';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/delivery_method/add.html.twig', [
            'controller_name' => 'DeliveryMethodController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление способа доставки'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param DeliveryMethod $delivery
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function deliveryEdit(DeliveryMethod $delivery, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(DeliveryMethodType::class, $delivery, [
            'action' => $this->generateUrl('admin_delivery_edit', ['id' => $delivery->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'delivery_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrData = $request->request->get('delivery_method');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($delivery, 'name', 'uk', $arrData['translation_name'])
                ->translate($delivery, 'short_description', 'uk', $arrData['translation_short_description'])
                ->translate($delivery, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($delivery);
            $entityManager->flush();

            $message = $translator->trans('Способ доставки успешно изменен');
            $this->addFlash('success', $message);

            if ($form->get('submitAndAdd')->isClicked()) {
                return $this->redirectToRoute('admin_delivery_add');
            } else {
                return $this->redirectToRoute('admin_delivery_single', ['id' => $delivery->getId()]);
            }
        }

        return $this->render('admin/delivery_method/add.html.twig', [
            'controller_name' => 'DeliveryMethodController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование способа доставки'),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param DeliveryMethod $delivery
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function deliveryDelete(DeliveryMethod $delivery, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($delivery);
        $entityManager->flush();

        $message = $translator->trans('Способ доставки успешно удален');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_delivery_all');
    }

    /**
     * @Route("/sort/{id}", name="_sort", requirements={"id"="\d+"})
     * @param Request $request
     * @param DeliveryMethod $delivery
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function deliverySort(Request $request, DeliveryMethod $delivery, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(SortableType::class, null, [
            'action' => $this->generateUrl('admin_delivery_sort', ['id' => $delivery->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty($data['sorted_data'])) {
                $arrayId = explode(',', $data['sorted_data']);
                foreach ($arrayId as $position=>$id) {
                    if ($delivery->getId() == $id) {
                        $delivery->setPosition($position);

                        $entityManager->persist($delivery);
                        $entityManager->flush();

                        break;
                    }
                }

                $this->addFlash('success', $translator->trans('Способы доставки успешно отсортированы'));
            } else {
                $this->addFlash('warning', $translator->trans('Ничего не изменено'));
            }
        }

        $deliveries = $entityManager->getRepository(DeliveryMethod::class)->findBy([], ['position' => 'ASC']);

        return $this->render('admin/delivery_method/sort.html.twig', [
            'controller_name' => 'DeliveryMethodController',
            'deliveries' => $deliveries,
            'current' => $delivery,
            'form_sort' => $form->createView(),
        ]);
    }
}
