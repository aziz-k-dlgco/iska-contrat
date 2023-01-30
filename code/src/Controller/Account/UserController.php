<?php

namespace App\Controller\Account;

use App\Entity\Account\User;
use App\Service\Account\CheckUserRole;
use App\Service\Account\GetUsersJuridique;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user')]
class UserController extends AbstractController
{
    // Retrieve all users granted with ROLE_USER_JURIDIQUE role
    #[Route('/user_juridique', name: 'app_account_user_juridique', methods: ['GET'])]
    public function userJuridique(
        GetUsersJuridique $getUsersJuridiqueSrv,
        CheckUserRole $checkUserRoleSrv
    ): JsonResponse
    {
        $data = array_map(
            function (User $user) use ($checkUserRoleSrv) {
                return !($checkUserRoleSrv)($user, 'ROLE_MANAGER_JURIDIQUE') ? [
                    'value' => $user->getId(),
                    'label' => $user->getLib() . ' (' . $user->getAdditionnalData()->getDelaiTraitementContrat()->d . ' jours)',
                ] : [];
            },
            ($getUsersJuridiqueSrv)()
        );
        // Remove empty values
        $data = array_filter($data);
        // detect associative array
        $isAssoc = array_keys($data) !== range(0, count($data) - 1);
        // If associative, convert to indexed array
        if ($isAssoc) {
            $data = array_values($data);
        }
        return new JsonResponse($data, Response::HTTP_OK, [
            'Cache-Control' => 'cache, max-age=300',
        ]);
    }
}
