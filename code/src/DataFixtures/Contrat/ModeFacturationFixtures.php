<?php

namespace App\DataFixtures\Contrat;

use App\DataFixtures\Dev\AdminAccountFixtures;
use App\Entity\Contrat\ModeFacturation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ModeFacturationFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            'Facturation Mensuelle',
            'Facturation Annuelle',
            'Facturation par phase',
            'Facturation par livrable',
            'Non renseigné'
        ];

        foreach ($data as $key => $value) {
            $modeFacturation = new ModeFacturation();
            $modeFacturation->setLib($value);
            $modeFacturation->setIsListable(true);
            $manager->persist($modeFacturation);
            $this->addReference('modeFacturation' . $key, $modeFacturation);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod', 'dev'];
    }

    public function getDependencies()
    {
        return array(
            AdminAccountFixtures::class,
        );
    }
}
