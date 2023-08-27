<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\City\CityEntity;
use App\Session\SessionEntity;
use App\Session\StageEntity;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SessionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('uk_UA');
        $city = $manager->getRepository(CityEntity::class)->findOneBy(['techName' => 'lviv']);

        $session = new SessionEntity(
            name: "Бюджет 2023",
            city: $city,
        );

        $manager->persist($session);
        $end = null;

        foreach (['submission', 'review', 'voting', 'decision', 'implementation'] as $key => $stageName){
            $stage = new StageEntity(
                $stageName,
                $session,
                true,
                $end
                    ? (clone $end)->modify('+1 day')->setTime(0,0)
                    : (new DateTime())->modify("+$key week")->setTime(0,0),
                $end = (new DateTime())->modify("+$key week")->modify('+2 week')->setTime(23,59),
            );

            $manager->persist($stage);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CityFixtures::class,
        ];
    }
}
