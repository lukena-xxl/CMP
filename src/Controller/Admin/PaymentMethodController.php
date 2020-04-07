<?php

namespace App\Controller\Admin;

use App\Entity\PaymentMethod;
use App\Form\Admin\Common\SortableType;
use App\Form\Admin\PaymentMethod\PaymentMethodType;
use App\Repository\PaymentMethodRepository;
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
 * Class PaymentMethodController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_SUPERADMIN", message="Access denied for you!")
 * @Route("/admin/payment", name="admin_payment")
 */
class PaymentMethodController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param PaymentMethodRepository $paymentMethodRepository
     * @return Response
     */
    public function paymentAll(PaymentMethodRepository $paymentMethodRepository)
    {
        return $this->render('admin/payment_method/all.html.twig', [
            'controller_name' => 'PaymentMethodController',
            'payments' => $paymentMethodRepository->findBy([], ['position' => 'ASC']),
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param PaymentMethod $paymentMethod
     * @return Response
     */
    public function paymentSingle(PaymentMethod $paymentMethod)
    {
        return $this->render('admin/payment_method/single.html.twig', [
            'controller_name' => 'PaymentMethodController',
            'payment' => $paymentMethod,
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function paymentAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(PaymentMethodType::class, null, [
            'action' => $this->generateUrl('admin_payment_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'payment_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payment = $form->getData();

            $arrData = $request->request->get('payment_method');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($payment, 'name', 'uk', $arrData['translation_name'])
                ->translate($payment, 'short_description', 'uk', $arrData['translation_short_description'])
                ->translate($payment, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($payment);
            $entityManager->flush();

            $message = $translator->trans('Способ оплаты успешно добавлен');
            $this->addFlash('success', $message);

            $nextAction = $form->get('submitAndAdd')->isClicked()
                ? 'admin_payment_add'
                : 'admin_payment_all';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/payment_method/add.html.twig', [
            'controller_name' => 'PaymentMethodController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление способа оплаты'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param PaymentMethod $payment
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function paymentEdit(PaymentMethod $payment, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(PaymentMethodType::class, $payment, [
            'action' => $this->generateUrl('admin_payment_edit', ['id' => $payment->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'payment_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrData = $request->request->get('payment_method');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($payment, 'name', 'uk', $arrData['translation_name'])
                ->translate($payment, 'short_description', 'uk', $arrData['translation_short_description'])
                ->translate($payment, 'description', 'uk', $arrData['translation_description']);

            $entityManager->persist($payment);
            $entityManager->flush();

            $message = $translator->trans('Способ оплаты успешно изменен');
            $this->addFlash('success', $message);

            if ($form->get('submitAndAdd')->isClicked()) {
                return $this->redirectToRoute('admin_payment_add');
            } else {
                return $this->redirectToRoute('admin_payment_single', ['id' => $payment->getId()]);
            }
        }

        return $this->render('admin/payment_method/add.html.twig', [
            'controller_name' => 'PaymentMethodController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Редактирование способа оплаты'),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param PaymentMethod $payment
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function paymentDelete(PaymentMethod $payment, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($payment);
        $entityManager->flush();

        $message = $translator->trans('Способ оплаты успешно удален');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_payment_all');
    }

    /**
     * @Route("/sort/{id}", name="_sort", requirements={"id"="\d+"})
     * @param Request $request
     * @param PaymentMethod $payment
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function paymentSort(Request $request, PaymentMethod $payment, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(SortableType::class, null, [
            'action' => $this->generateUrl('admin_payment_sort', ['id' => $payment->getId()]),
            'method' => 'post',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty($data['sorted_data'])) {
                $arrayId = explode(',', $data['sorted_data']);
                foreach ($arrayId as $position=>$id) {
                    if ($payment->getId() == $id) {
                        $payment->setPosition($position);

                        $entityManager->persist($payment);
                        $entityManager->flush();

                        break;
                    }
                }

                $this->addFlash('success', $translator->trans('Способы оплаты успешно отсортированы'));
            } else {
                $this->addFlash('warning', $translator->trans('Ничего не изменено'));
            }
        }

        $payments = $entityManager->getRepository(PaymentMethod::class)->findBy([], ['position' => 'ASC']);

        return $this->render('admin/payment_method/sort.html.twig', [
            'controller_name' => 'PaymentMethodController',
            'payments' => $payments,
            'current' => $payment,
            'form_sort' => $form->createView(),
        ]);
    }
}
