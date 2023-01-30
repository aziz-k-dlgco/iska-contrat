<?php

namespace App\Service\Account;

use App\Entity\Account\User;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class CheckUserRole
{
    public function __construct(
        private RoleHierarchyInterface $roleHierarchy,
    )
    {
    }

    public function __invoke(User $user, string $roleToCheck) : bool
    {
        $roles = $this->roleHierarchy->getReachableRoleNames($user->getRoles());
        foreach ($roles as $role) {
            if ($role === $roleToCheck) {
                return true;
            }
        }
        return false;
    }
}