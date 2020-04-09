<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use App\Form\Admin\Order\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class OrderController
 * @package App\Controller\Admin
 * @Route("/admin/order", name="admin_order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param OrderRepository $orderRepository
     * @return Response
     */
    public function orderAll(OrderRepository $orderRepository)
    {
        $user = $this->getUser();

        return $this->render('admin/order/all.html.twig', [
            'controller_name' => 'OrderController',
            'orders' => $orderRepository->adminOrdersList($user),
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param Orders $order
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function orderSingle(Orders $order, TranslatorInterface $translator)
    {
        if ($this->getUser() === $order->getAdmin() || $this->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('admin/order/single.html.twig', [
                'controller_name' => 'OrderController',
                'order' => $order,
            ]);
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param ParameterBagInterface $parameterBag
     * @return RedirectResponse|Response
     */
    public function orderAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, ParameterBagInterface $parameterBag)
    {
        $form = $this->createForm(OrderType::class, null, [
            'action' => $this->generateUrl('admin_order_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'order_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();

            $user = $this->getUser();

            $order->setUser($user);
            $order->setAdmin($user);

            $entityManager->persist($order);
            $entityManager->flush();

            $message = $translator->trans('Заказ успешно создан');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_order_single', ['id' => $order->getId()]);
        }

        return $this->render('admin/order/add.html.twig', [
            'controller_name' => 'OrderController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление заказа'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param Orders $order
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param ParameterBagInterface $parameterBag
     * @return RedirectResponse|Response
     */
    public function orderEdit(Orders $order, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, ParameterBagInterface $parameterBag)
    {
        $user = $this->getUser();

        if ($user === $order->getAdmin() || $this->isGranted('ROLE_SUPERADMIN')) {
            $form = $this->createForm(OrderType::class, $order, [
                'action' => $this->generateUrl('admin_order_edit', ['id' => $order->getId()]),
                'method' => 'post',
                'attr' => [
                    'id' => 'order_form',
                ],
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($order);
                $entityManager->flush();

                $message = $translator->trans('Заказ успешно изменен');
                $this->addFlash('success', $message);

                return $this->redirectToRoute('admin_order_single', ['id' => $order->getId()]);
            }

            return $this->render('admin/order/add.html.twig', [
                'controller_name' => 'OrderController',
                'form_add' => $form->createView(),
                'title' => $translator->trans('Редактирование заказа') . ' ' . $order->getId(),
            ]);
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param Orders $order
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function orderDelete(Orders $order, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $user = $this->getUser();

        if ($user === $order->getAdmin() || $this->isGranted('ROLE_SUPERADMIN')) {
            $entityManager->remove($order);
            $entityManager->flush();

            $message = $translator->trans('Заказ успешно удален');

            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_order_all');
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }
}
