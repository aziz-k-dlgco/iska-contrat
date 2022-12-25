<?php

namespace App\DataFixtures\Dev;

use App\Entity\Account\Departement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AdminDepartementFixtures extends Fixture implements FixtureGroupInterface
{
    public const REF = 'admin-departement-fixtures';

    public function load(ObjectManager $manager): void
    {
        $departement = new Departement();
        $departement->setNom('Admin');
        $manager->persist($departement);
        $manager->flush();

        $this->addReference(self::REF, $departement);
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
