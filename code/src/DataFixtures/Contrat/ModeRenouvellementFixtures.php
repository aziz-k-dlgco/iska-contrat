<?php

namespace App\DataFixtures\Contrat;

use App\Entity\Contrat\ModeRenouvellement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ModeRenouvellementFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            'Tacite reconduction', 'Express',
            'Non renseigné'
        ];

        foreach ($data as $modeRenouvellement) {
            $modeRenouvellementE = new ModeRenouvellement();
            $modeRenouvellementE->setLib($modeRenouvellement);
            $modeRenouvellementE->setIsListable(true);
            $manager->persist($modeRenouvellementE);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod', 'dev'];
    }
}
