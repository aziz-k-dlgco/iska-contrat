<?php

namespace App\DataFixtures\Contrat;

use App\Entity\Contrat\ModeFacturation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ModeFacturationFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            'Facturation Mensuelle',
            'Facturation Annuelle',
            'Facturation par phase',
            'Facturation par livrable'
        ];

        foreach ($data as $key => $value) {
            $modeFacturation = new ModeFacturation();
            $modeFacturation->setLib($value);
            $manager->persist($modeFacturation);
            $this->addReference('modeFacturation' . $key, $modeFacturation);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}
