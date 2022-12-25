<?php

namespace App\DataFixtures\Dev;

use App\Entity\Account\Departement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class DepartementFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'nom' => 'Departement 1',
                'ref' => 'departement1',
            ],
            [
                'nom' => 'Departement juridique',
                'ref' => 'departement-juridique',
            ],
        ];

        foreach ($data as $departement) {
            $departementE = new Departement();
            $departementE->setNom($departement['nom']);
            $manager->persist($departementE);
            $manager->flush();

            $this->addReference($departement['ref'], $departementE);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
