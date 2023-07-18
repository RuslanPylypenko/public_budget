<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\City\CityEntity;
use App\User\ConfirmToken;
use App\User\UserEntity;
use App\Utils\DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// php bin/console doctrine:fixtures:load
class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');

        for ($i = 1; $i <= 5; $i++) {
            $user = new UserEntity(
                name: $faker->firstName(),
                surname: $faker->name(),
                patronymic: $faker->lastName(),
                email: $faker->email(),
                birthday: $faker->dateTimeBetween('-50 years', '-10 years'),
                confirmToken: new ConfirmToken('token', DateTime::current()->modify('+1 day')),
                passport: sprintf('%s%s', $faker->randomElement(['KA', 'НВ', 'НЕ']), $faker->numberBetween(100000, 999999)),
                phone: substr($faker->phoneNumber(), 0, 12),
            );

            $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));

            $user->setConfirmToken(null);
            $user->setStatus($user::STATUS_ACTIVE);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
