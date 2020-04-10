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
     * @return Response
     */
    public function userSingle(User $user)
    {
        return $this->render('admin/user/single.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
        ]);
    }

    /**
     * @Route("/add", name="_add")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return Response
     * @IsGranted("ROLE_SUPERADMIN")
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
     * @IsGranted("ROLE_SUPERADMIN")
     */
    public function userEdit(User $user, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('admin_user_edit', ['id' => $user->getId()]),
            'method' => 'post',
            'attr' => [
                'id' => 'user_form',
            ],
        ])->remove('password');

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
    }

    /**
     * @Route("/delete/{id}", name="_delete", requirements={"id"="\d+"})
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     * @IsGranted("ROLE_SUPERADMIN")
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
