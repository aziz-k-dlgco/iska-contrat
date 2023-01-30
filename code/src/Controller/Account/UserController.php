<?php

namespace App\Controller\Account;

use App\Entity\Account\User;
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
    public function userJuridique(GetUsersJuridique $getUsersJuridiqueSrv): JsonResponse
    {
        return new JsonResponse(array_map(
            function (User $user) {
                return [
                    'value' => $user->getId(),
                    'label' => $user->getLib() . ' (' . $user->getAdditionnalData()->getDelaiTraitementContrat()->d . ' jours)',
                ];
            },
            ($getUsersJuridiqueSrv)()
        ), Response::HTTP_OK, [
            'Cache-Control' => 'cache, max-age=300',
        ]);
    }
}
