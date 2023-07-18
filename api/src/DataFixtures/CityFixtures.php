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
        $manager->flush();
    }
}
