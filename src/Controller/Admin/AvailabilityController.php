<?php

namespace App\Controller\Admin;

use App\Entity\Availability;
use App\Form\Admin\Availability\AvailabilityType;
use App\Repository\AvailabilityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AvailabilityController
 * @package App\Controller\Admin
 * @Route("/admin/availability", name="admin_availability")
 */
class AvailabilityController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param AvailabilityRepository $availabilityRepository
     * @return Response
     */
    public function availabilityAll(AvailabilityRepository $availabilityRepository)
    {
        $user = $this->getUser();

        return $this->render('admin/availability/all.html.twig', [
            'controller_name' => 'AvailabilityController',
            'availabilities' => $availabilityRepository->adminAvailabilitiesList($user),
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param Availability $availability
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function availabilitySingle(Availability $availability, TranslatorInterface $translator)
    {
        if ($this->getUser() === $availability->getUser() || $this->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('admin/availability/single.html.twig', [
                'controller_name' => 'AvailabilityController',
                'availability' => $availability,
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
     * @return RedirectResponse|Response
     */
    public function availabilityAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(AvailabilityType::class, null, [
            'action' => $this->generateUrl('admin_availability_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'availability_form',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $availability = $form->getData();

            $arrData = $request->request->get('availability');
            $repoTranslation = $entityManager->getRepository(Translation::class);
            $repoTranslation->translate($availability, 'name', 'uk', $arrData['translation_name'])
                ->translate($availability, 'short_description', 'uk', $arrData['translation_short_description'])
                ->translate($availability, 'description', 'uk', $arrData['translation_description']);

            $user = $this->getUser();
            $availability->setUser($user);

            $entityManager->persist($availability);
            $entityManager->flush();

            $message = $translator->trans('Доступность успешно добавлена');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_availability_all');
        }

        return $this->render('admin/availability/add.html.twig', [
            'controller_name' => 'AvailabilityController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление доступности продукта'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param Availability $availability
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function availabilityEdit(Availability $availability, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $user = $this->getUser();

        if ($user === $availability->getUser() || $this->isGranted('ROLE_SUPERADMIN')) {
            $form = $this->createForm(AvailabilityType::class, $availability, [
                'action' => $this->generateUrl('admin_availability_edit', ['id' => $availability->getId()]),
                'method' => 'post',
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $arrData = $request->request->get('availability');
                $repoTranslation = $entityManager->getRepository(Translation::class);
                $repoTranslation->translate($availability, 'name', 'uk', $arrData['translation_name'])
                    ->translate($availability, 'short_description', 'uk', $arrData['translation_short_description'])
                    ->translate($availability, 'description', 'uk', $arrData['translation_description']);

                $entityManager->persist($availability);
                $entityManager->flush();

                $message = $translator->trans('Доступность успешно изменена');
                $this->addFlash('success', $message);

                return $this->redirectToRoute('admin_availability_single', ['id' => $availability->getId()]);
            }

            return $this->render('admin/availability/add.html.twig', [
                'controller_name' => 'AvailabilityController',
                'form_add' => $form->createView(),
                'title' => $translator->trans('Редактирование доступности продукта'),
            ]);
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param Availability $availability
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function availabilityDelete(Availability $availability, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $user = $this->getUser();

        if ($user === $availability->getUser() || $this->isGranted('ROLE_SUPERADMIN')) {
            $entityManager->remove($availability);
            $entityManager->flush();

            $message = $translator->trans('Доступность успешно удалена');

            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_availability_all');
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }
}
