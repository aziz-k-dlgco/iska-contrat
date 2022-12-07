<?php

namespace App\DataFixtures\Dev;

use App\Entity\Account\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AdminAccountFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@redoc.azk');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
