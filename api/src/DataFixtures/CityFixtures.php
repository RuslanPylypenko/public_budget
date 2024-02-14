<?php

declare(strict_types=1);

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

        $city = new CityEntity(
            "Львів",
            'lviv',
            "Платформа реалізації ідей для покращення твого міста",
            'Бюджет міських ініціатив дозволяє мешканцю взяти участь в тому, як і де бюджетні кошти 
                можуть використовуватися для поліпшення життя міста. Подавайте свої проекти і голосуйте за цікаві ідеї. 
                Проекти, які найбільше підтримають мешканці, будуть реалізовані з бюджету мiста.',
            49.842957,
            24.031111,
        );
        $manager->persist($city);

        $city = new CityEntity(
            "Київ",
            'kyiv',
            "Бюджет участі у м. Київ",
            'Зміни своє місто! Подай свій проєкт та отримай гроші на реалізацію!',
            50.450001,
            30.523333,
        );

        $manager->persist($city);
        $manager->flush();

        for ($i = 0; $i <= 1; $i++) {
            $city = new CityEntity(
                $city = $faker->city,
                $faker->slug(1),
                "Бюджет участі у м. " . $city,
                $faker->text(),
                $faker->latitude(min: 50.430001, max: 50.470001),
                $faker->latitude(min: 30.503333, max: 30.543333),
            );
            $manager->persist($city);
        }

        $manager->flush();
    }
}
