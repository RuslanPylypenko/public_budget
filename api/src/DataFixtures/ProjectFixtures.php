<?php

namespace App\DataFixtures;

use App\City\CityEntity;
use App\Project\Address\AddressEntity;
use App\Project\ProjectEntity;
use App\User\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('uk_UA');

        $users = $manager->getRepository(UserEntity::class)
            ->findAll();

        /** @var CityEntity $city */
        $city = $manager->getRepository(CityEntity::class)->findOneBy(['techName' => 'lviv']);

        var_dump($city->getSessions());
        die();

        for ($i = 1; $i <= 10; $i++) {

            $address = new AddressEntity(
                'Львівська область',
                'Львів',
                $faker->streetName,
                $faker->latitude(49.832689, 49.8881351),
                $faker->longitude(24.0632909, 24.0924831),
            );

            $manager->persist($address);

            $project = new ProjectEntity(
                number: $i,
                status: $faker->randomElement([
                    ProjectEntity::STATUS_IMPLEMENTED,
                    ProjectEntity::STATUS_IMPOSSIBLE,
                    ProjectEntity::STATUS_REJECTED,
                    ProjectEntity::STATUS_TAKE_PART,
                    ProjectEntity::STATUS_REJECTED_FULLY,
                ]),
                budget: $faker->randomFloat(min: 10000, max: 1000000),
                name: $faker->words(rand(3, 6), true),
                short: $faker->sentences(5, true),
                description: $faker->sentences(12, true),
                author: $faker->randomElement($users),
                session: $city->getCurrentSession(),
                address: $address,
               );
        }
    }

    public function getDependencies(): array
    {
        return [
            SessionFixtures::class,
        ];
    }
}