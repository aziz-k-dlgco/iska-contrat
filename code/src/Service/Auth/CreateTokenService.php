<?php

namespace App\Service\Auth;

use App\Repository\Account\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class CreateTokenService
{
    public function __construct(
        private UserRepository $userRepository,
        private JWTTokenManagerInterface $JWTManager,
    )
    {
    }

    public function __invoke(
        string $identifiant,
    ) : string
    {
        $user = $this->userRepository->findOneBy(['identifiant' => $identifiant]);
        if (!$user) {
            throw new \Exception('Utilisateur non trouvé');
        }

        if (!$user->isIsActive()){
            throw new \Exception('Utilisateur non actif');
        }

        $user->setLastConnection(new \DateTime());
        $this->userRepository->save($user);

        return $this->JWTManager->create($user);
    }
}