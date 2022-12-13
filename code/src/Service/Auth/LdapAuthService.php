<?php

namespace App\Service\Auth;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Ldap\Ldap;

class LdapAuthService
{
    public function __construct(
        private LoggerInterface $logger,
        private KernelInterface $kernel,
    )
    {
    }

    public function __invoke(
        string $username,
        string $password,
    ) : bool
    {
        $this->logger->info("[LDAP Authentification] - Start - {$username}");
        //Check if dev mode
        if ($this->kernel->getEnvironment() === 'dev') {
            $this->logger->info("[LDAP Authentification] - Dev mode - {$username} - OK");
            return true;
        }

        $ldap_server = '192.168.1.188';

        // Connexion au serveur LDAP
        $ldap = Ldap::create('ext_ldap', [
            'host' => $ldap_server,
            'port' => 389,
            'encryption' => 'none',
            'options' => [
                'protocol_version' => 3,
                'referrals' => false,
            ],
        ]);

        try{
            $this->logger->info('[LDAP Authentification] - Bind');
            $ldap->bind($username, $password);
            $this->logger->info('[LDAP Authentification] - Bind - Success');
            return true;
        } catch (\Exception $e) {
            $this->logger->info('[LDAP Authentification] - Bind - Failure');
            return false;
        }
    }
}