<?php

namespace App\DataFixtures\Contrat;

use App\Entity\Contrat\TypeContrat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TypeContratFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            'Construction', 'Maintenance', 'Prestation / Service', 'Transport de fonds',
            'Vente', 'Entretien / Nettoyage', "Demande d'autorisation", 'Bail',
            'Construction', 'Accord de confidentialité',
            'Non renseigné'
        ];

        foreach ($types as $type) {
            $typeContrat = new TypeContrat();
            $typeContrat->setLib($type);
            $manager->persist($typeContrat);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod', 'dev'];
    }
}
