<?php

namespace App\DataFixtures\Dev;

use App\DataFixtures\Account\RolesFixtures;
use App\Entity\Account\Departement;
use App\Entity\Account\Roles;
use App\Entity\Account\User;
use App\Entity\Account\UserData;
use Carbon\CarbonInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AccountFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'nom' => 'User',
                'prenoms' => 'simple',
                'email' => 'usimple@redoc.azk',
                'identifiant' => 'usimple',
                'departement' => 'departement1',
                'isActive' => true,
                'role' => 'role_user',
            ],
            [
                'nom' => 'User',
                'prenoms' => 'manager',
                'email' => 'umanager@redoc.azk',
                'identifiant' => 'umanager',
                'departement' => 'departement1',
                'isActive' => true,
                'role' => 'role_manager',
            ],
            [
                'nom' => 'User',
                'prenoms' => 'juridique',
                'email' => 'ujuridique@redoc.azk',
                'identifiant' => 'ujuridique',
                'departement' => 'departement-juridique',
                'isActive' => true,
                'role' => 'role_user_juridique',
            ],
            [
                'nom' => 'User',
                'prenoms' => 'manager juridique',
                'email' => 'mjuridique@redoc.azk',
                'identifiant' => 'mjuridique',
                'departement' => 'departement-juridique',
                'isActive' => true,
                'role' => 'role_manager_juridique',
            ],
        ];

        foreach ($data as $account) {
            /**
             * @var Roles $role
             */
            $role = $this->getReference($account['role']);
            /**
             * @var Departement $departement
             */
            $departement = $this->getReference($account['departement']);
            $accountE = new User();
            $accountE->setNom($account['nom']);
            $accountE->setPrenoms($account['prenoms']);
            $accountE->setEmail($account['email']);
            $accountE->setIdentifiant($account['identifiant']);
            $accountE->setDepartement($departement);
            $accountE->setIsActive($account['isActive']);
            $accountE->setRoles([$role->getLib()]);
            $accountE->setAdditionnalData(
                (new UserData())
                ->setDelaiTraitementContrat((CarbonInterval::days(14))->toDateInterval())
            );
            $manager->persist($accountE);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DepartementFixtures::class,
            RolesFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
