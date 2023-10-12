<?php

namespace App\DataFixtures;

use App\City\CityEntity;
use App\Project\Address\AddressEntity;
use App\Project\ProjectEntity;
use App\Session\SessionEntity;
use App\User\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('uk_UA');

        $users = $manager->getRepository(UserEntity::class)
            ->findAll();

        /** @var CityEntity $city */
        $sessions = $manager->getRepository(SessionEntity::class)->findAll();

        foreach ($sessions as $session){
            for ($i = 1; $i <= 100; $i++) {

                $project = new ProjectEntity(
                    number: $i,
                    status: $faker->randomElement([
                        ProjectEntity::STATUS_IMPLEMENTED,
                        ProjectEntity::STATUS_IMPOSSIBLE,
                        ProjectEntity::STATUS_REJECTED,
                        ProjectEntity::STATUS_TAKE_PART,
                        ProjectEntity::STATUS_REJECTED_FULLY,
                    ]),
                    budget: $faker->randomFloat(nbMaxDecimals: 2, min: 10000, max: 1000000),
                    name: $faker->sentence(5),
                    short: $faker->sentences(5, true),
                    description: $faker->sentences(12, true),
                    author: $faker->randomElement($users),
                    session: $session,
                );

                $city = $session->getCity();

                if ($faker->boolean()) {
                    $address = new AddressEntity(
                        $project,
                        null,
                        null,
                        null,
                        $city->getName(),
                        null,
                        "вул. " . $faker->streetName(),
                        $faker->buildingNumber(),
                        $faker->boolean() ? "кв. " . $faker->numberBetween(1, 120) : null,
                        null,
                        $faker->latitude($city->getLat() - 0.02, $city->getLat() + 0.02),
                        $faker->longitude($city->getLon() - 0.02, $city->getLon() + 0.02),
                    );

                    $manager->persist($address);
                }

                $manager->persist($project);
            }
        }



        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SessionFixtures::class,
            UserFixtures::class,
        ];
    }
}