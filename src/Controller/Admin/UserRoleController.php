<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserRoleController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_SUPERADMIN", message="Access denied for you!")
 * @Route("/admin/user/role", name="admin_user_role")
 */
class UserRoleController extends AbstractController
{
    /**
     * @Route("", name="_all")
     * @param UserRepository $userRepository
     * @param $userRoles
     * @return Response
     */
    public function userRoleAll(UserRepository $userRepository, $userRoles)
    {
        $roles = [];
        foreach ($userRoles as $role) {
            $roles[] = ['role' => $role, 'users' => count($userRepository->getUsersWithRole($role))];
        }

        return $this->render('admin/user_role/all.html.twig', [
            'controller_name' => 'UserRoleController',
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("/{role}", name="_single")
     * @param UserRepository $userRepository
     * @param TranslatorInterface $translator
     * @param $userRoles
     * @param $role
     * @return Response
     */
    public function userRoleSingle(UserRepository $userRepository, TranslatorInterface $translator, $userRoles, $role)
    {
        if (!in_array($role, $userRoles)) {
            $message = $translator->trans('Запрашиваемая роль не существует');
            $this->addFlash('danger', $message);

            return $this->redirectToRoute('admin_user_role_all');
        }

        $users = $userRepository->getUsersWithRole($role);
        $parsRole = explode('ROLE_', $role);

        return $this->render('admin/user_role/single.html.twig', [
            'controller_name' => 'UserRoleController',
            'users' => $users,
            'role' => $parsRole[1],
        ]);
    }
}
