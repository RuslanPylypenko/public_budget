<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\City\CityEntity;
use App\Session\SessionEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SessionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $city = $manager->getRepository(CityEntity::class)->findOneBy(['techName' => 'lviv']);

       $session = new SessionEntity(
           name: "Бюджет 2023",
           city: $city,
       );

       $manager->persist($session);
       $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CityFixtures::class,
        ];
    }
}
