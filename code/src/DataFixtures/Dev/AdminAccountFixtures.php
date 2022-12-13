<?php

namespace App\DataFixtures\Dev;

use App\Entity\Account\Departement;
use App\Entity\Account\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AdminAccountFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /**
         * @var Departement $departement
         */
        $departement = $this->getReference(AdminDepartementFixtures::REF);

        $user = new User();
        $user->setNom('Admin');
        $user->setPrenoms('Admin');
        $user->setEmail('admin@redoc.azk');
        $user->setIdentifiant('admin');
        $user->setDepartement($departement);
        $user->setIsActive(true);
        $manager->persist($user);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function getDependencies()
    {
        return [
          AdminDepartementFixtures::class
        ];
    }
}
