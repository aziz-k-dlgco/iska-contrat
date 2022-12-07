<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'dlg:check-ldap',
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
        // dc=redoc,dc=azk
        $io = new SymfonyStyle($input, $output);
        $enteredUsername = $input->getOption('username');
        $ldap_bind_password = $input->getOption('password');
        $ldap_server = 'ldap://monad:389';
        $ldap_dn = "cn={$enteredUsername},dc=redoc,dc=azk";

        // Connexion au serveur LDAP
        $ldap = ldap_connect($ldap_server);
        //use ldap v3
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        if ($ldap) {
            // Connexion au serveur LDAP
            $ldap_bind = ldap_bind($ldap, $ldap_dn, $ldap_bind_password);
            if ($ldap_bind) {
                $io->success("User {$enteredUsername} exists in LDAP");
            } else {
                $io->error("User {$enteredUsername} does not exist in LDAP");
            }
        } else {
            $io->error("Unable to connect to LDAP server");
        }

        return Command::SUCCESS;
    }
}
