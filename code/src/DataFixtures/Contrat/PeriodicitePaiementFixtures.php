<?php

namespace App\DataFixtures\Contrat;

use App\Entity\Contrat\PeriodicitePaiement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PeriodicitePaiementFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = ['Mensuel', 'Trimestriel', 'Semestriel', 'Annuel'];

        foreach ($data as $periodicite) {
            $periodiciteE = new PeriodicitePaiement();
            $periodiciteE->setLib($periodicite);
            $periodiciteE->setIsListable(true);
            $manager->persist($periodiciteE);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod', 'dev'];
    }
}
