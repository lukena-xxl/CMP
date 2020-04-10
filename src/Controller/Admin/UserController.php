<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\User\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController
 * @package App\Controller\Admin
 * @Route("/admin/user", name="admin_user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @IsGranted("ROLE_SUPERADMIN", message="Access denied for you!")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function userAll(UserRepository $userRepository)
    {
        $users = $userRepository->findBy([], ['registration_date' => 'DESC']);

        return $this->render('admin/user/all.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }

    /**
     * @Route("/{id}", name="_single", requirements={"id"="\d+"})
     * @param User $user
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function userSingle(User $user, TranslatorInterface $translator)
    {
        $current_user = $this->getUser();

        if ($current_user === $user || $this->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('admin/user/single.html.twig', [
                'controller_name' => 'UserController',
                'user' => $user,
            ]);
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }

    /**
     * @Route("/add", name="_add")
     * @IsGranted("ROLE_SUPERADMIN", message="Access denied for you!")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function userAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(UserType::class, null, [
            'action' => $this->generateUrl('admin_user_add'),
            'method' => 'post',
            'attr' => [
                'id' => 'user_form',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $entityManager->persist($user);
            $entityManager->flush();

            $message = $translator->trans('Пользователь успешно добавлен');
            $this->addFlash('success', $message);

            return $this->redirectToRoute('admin_user_all');
        }

        return $this->render('admin/user/add.html.twig', [
            'controller_name' => 'UserController',
            'form_add' => $form->createView(),
            'title' => $translator->trans('Добавление пользователя'),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit", requirements={"id"="\d+"})
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function userEdit(User $user, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $current_user = $this->getUser();

        if ($current_user === $user || $this->isGranted('ROLE_SUPERADMIN')) {
            $form = $this->createForm(UserType::class, $user, [
                'action' => $this->generateUrl('admin_user_edit', ['id' => $user->getId()]),
                'method' => 'post',
                'attr' => [
                    'id' => 'user_form',
                ],
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($user);
                $entityManager->flush();

                $message = $translator->trans('Пользователь успешно изменен');
                $this->addFlash('success', $message);

                return $this->redirectToRoute('admin_user_single', ['id' => $user->getId()]);
            }

            return $this->render('admin/user/add.html.twig', [
                'controller_name' => 'UserController',
                'form_add' => $form->createView(),
                'title' => $translator->trans('Редактирование пользователя'),
                'no_password' => true,
            ]);
        } else {
            throw new AccessDeniedException($translator->trans('У вас нет доступа для данной операции'));
        }
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @IsGranted("ROLE_SUPERADMIN", message="Access denied for you!")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function userDelete(User $user, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $entityManager->remove($user);
        $entityManager->flush();

        $message = $translator->trans('Пользователь успешно удален');

        $this->addFlash('success', $message);

        return $this->redirectToRoute('admin_user_all');
    }
}
