<?php

namespace App\Controller\Admin;

use App\Entity\Coefficient;
use App\Form\Admin\Coefficient\CoefficientType;
use App\Repository\CoefficientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CoefficientController
 * @package App\Controller\Admin
 * @Route("/admin/coefficient", name="admin_coefficient")
 */
class CoefficientController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param CoefficientRepository $coefficientRepository
     * @return Response
     */
    public function coefficientAll(CoefficientRepository $coefficientRepository)
    {
        $user = $this->getUser();

        return $this->render('admin/coefficient/all.html.twig', [
            'controller_name' => 'CoefficientController',
            'coefficients' => $coefficientRepository->adminCoefficientsList($user),
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function coefficientAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(CoefficientType::class, null, [
            'action' => $this->generateUrl('admin_coefficient_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'coefficient_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coefficient = $form->getData();

            $user = $this->getUser();
            $coefficient->setUser($user);

            $entityManager->persist($coefficient);
            $entityManager->flush();

            $message = $translator->trans('Коэффициент успешно добавлен');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_coefficient_all');
        }

        return $this->render('admin/coefficient/add.html.twig', [
            'controller_name' => 'CoefficientController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление коэффициента'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param Coefficient $coefficient
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function coefficientEdit(Coefficient $coefficient, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $user = $this->getUser();

        if ($user === $coefficient->getUser() || $this->isGranted('ROLE_SUPERADMIN')) {
            $form = $this->createForm(CoefficientType::class, $coefficient, [
                'action' => $this->generateUrl('admin_coefficient_edit', ['id' => $coefficient->getId()]),
                'method' => 'post',
                'attr' => [
                    'id' => 'coefficient_form',
                ],
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($coefficient);
                $entityManager->flush();

                $message = $translator->trans('Коэффициент успешно изменен');
                $this->addFlash('success', $message);

                return $this->redirectToRoute('admin_coefficient_edit', ['id' => $coefficient->getId()]);
            }

            return $this->render('admin/coefficient/add.html.twig', [
                'controller_name' => 'CoefficientController',
                'form_add' => $form->createView(),
                'title' => $translator->trans('Редактирование коэффициента'),
            ]);
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param Coefficient $coefficient
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function availabilityDelete(Coefficient $coefficient, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $user = $this->getUser();

        if ($user === $coefficient->getUser() || $this->isGranted('ROLE_SUPERADMIN')) {
            $entityManager->remove($coefficient);
            $entityManager->flush();

            $message = $translator->trans('Коэффициент успешно удален');

            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_coefficient_all');
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }
}
