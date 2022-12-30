<?php

namespace App\DataFixtures\Contrat;

use App\Entity\Contrat\ModePaiement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ModePaiementFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            'Paiement par chèque',
            'Paiement par virement',
            'Non renseigné'
        ];

        foreach ($data as $item) {
            $modePaiement = new ModePaiement();
            $modePaiement->setLib($item);
            $manager->persist($modePaiement);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod', 'dev'];
    }
}
