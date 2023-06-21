<?php

namespace App\DataFixtures;

use App\City\CityEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

// php bin/console doctrine:fixtures:load
class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');

        for ($i = 0; $i < 2; $i++) {
            $city = new CityEntity(
                $cityName = $faker->city,
                $faker->slug(1),
                $faker->words(rand(3,6), true),
                $faker->sentences(5, true),
                $faker->latitude,
                $faker->longitude,
            );
            $manager->persist($city);
        }

        $manager->flush();
    }
}
