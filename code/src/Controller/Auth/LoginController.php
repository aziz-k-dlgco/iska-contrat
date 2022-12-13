<?php

namespace App\Controller\Auth;

use App\Repository\Account\UserRepository;
use App\Service\Auth\CreateTokenService;
use App\Service\Auth\LdapAuthService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/auth', name: 'api_auth_login', methods: ['POST'])]
    public function auth(
        Request $request,
        UserRepository $userRepository,
        LdapAuthService $ldapAuthService,
        CreateTokenService $createTokenService,
    ): JsonResponse
    {
        try {
            //Get data from json
            $data = json_decode($request->getContent(), true);
            $username = $data['username'];
            $password = $data['password'];

            $user = $userRepository->findOneBy(['identifiant' => $username]);
            if (!$user) {
                throw new \Exception('Utilisateur non trouvé');
            }

            //Check if user is in LDAP
            if (!$ldapAuthService($username, $password)) {
                return $this->json([
                    'message' => 'Identifiant ou mot de passe incorrect',
                ], 401);
            }

            return $this->json([
                'token' => $createTokenService($username)
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], 401);
        }
    }
}
