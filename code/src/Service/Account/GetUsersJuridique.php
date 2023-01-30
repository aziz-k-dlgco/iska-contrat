<?php

namespace App\Service\Account;

use App\Entity\Account\User;
use App\Repository\Account\UserRepository;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * Service pour récupérer les utilisateurs ayant le rôle ROLE_USER_JURIDIQUE
 */
class GetUsersJuridique
{
    public function __construct(
        private RoleHierarchyInterface $roleHierarchy,
        private UserRepository $userRepository)
    {
    }

    public function __invoke() : array
    {
        $finalUsers = [];
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $roles = $this->roleHierarchy->getReachableRoleNames($user->getRoles());
            foreach ($roles as $role) {
                if ($role === 'ROLE_USER_JURIDIQUE') {
                    $finalUsers[] = $user;
                }
            }
        }

        return $finalUsers;
    }

    public function checkUserJuridique(User $user) : bool
    {
        $roles = $this->roleHierarchy->getReachableRoleNames($user->getRoles());
        foreach ($roles as $role) {
            if ($role === 'ROLE_USER_JURIDIQUE') {
                return true;
            }
        }
        return false;
    }
}