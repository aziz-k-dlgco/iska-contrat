<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Ldap\Ldap;

#[AsCommand(
    name: 'dlg:d',
    description: 'Check user exists in LDAP',
)]
class DlgCheckLdapCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('username', null, InputOption::VALUE_REQUIRED, 'Username')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // domain azkam.lan
        $io = new SymfonyStyle($input, $output);
        $username = $input->getOption('username') . '@AZKAM';
        $password = $input->getOption('password');
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
            $ldap->bind($username, $password);

            $io->success('User exists in LDAP');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('User does not exist in LDAP');
            return Command::FAILURE;
        }
    }
}
