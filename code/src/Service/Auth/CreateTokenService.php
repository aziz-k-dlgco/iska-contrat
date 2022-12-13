<?php

namespace App\Service\Auth;

use App\Repository\Account\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;

class CreateTokenService
{
    public function __construct(
        private UserRepository $userRepository,
        private JWTTokenManagerInterface $JWTManager,
        private LoggerInterface $logger
    )
    {
    }

    public function __invoke(
        string $identifiant,
    ) : string
    {
        $this->logger->info("[Create Token Service] Création du token pour l'utilisateur {$identifiant}");
        $user = $this->userRepository->findOneBy(['identifiant' => $identifiant]);
        if (!$user) {
            $this->logger->error("[Create Token Service] Utilisateur {$identifiant} non trouvé");
            throw new \Exception('Utilisateur non trouvé');
        }

        if (!$user->isIsActive()){
            $this->logger->error("[Create Token Service] Utilisateur {$identifiant} non actif");
            throw new \Exception('Utilisateur non actif');
        }

        $this->logger->info("[Create Token Service] Token créé pour l'utilisateur {$identifiant}");
        $user->setLastConnection(new \DateTime());
        $this->userRepository->save($user);
        return $this->JWTManager->create($user);
    }
}