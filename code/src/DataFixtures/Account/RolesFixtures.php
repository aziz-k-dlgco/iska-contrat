<?php

namespace App\DataFixtures\Account;

use App\Entity\Account\Roles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RolesFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'lib' => 'ROLE_USER',
                'description' => 'Utilisateur de base',
                'reference' => 'role_user',
            ],
            [
                'lib' => 'ROLE_MANAGER',
                'description' => 'Chef de département / Personne ressource dans le département',
                'reference' => 'role_manager',
            ],
            [
                'lib' => 'ROLE_USER_JURIDIQUE',
                'description' => 'Utilisateur de base du département juridique',
                'reference' => 'role_user_juridique',
            ],
            [
                'lib' => 'ROLE_MANAGER_JURIDIQUE',
                'description' => 'Chef de département juridique',
                'reference' => 'role_manager_juridique',
            ],
        ];

        foreach ($data as $role) {
            $roleE = new Roles();
            $roleE->setLib($role['lib']);
            $roleE->setDescription($role['description']);
            $manager->persist($roleE);
            $this->addReference($role['reference'], $roleE);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod', 'dev'];
    }
}
