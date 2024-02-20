<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Admin\AdminEntity;
use App\User\ConfirmToken;
use App\User\UserEntity;
use App\Utils\DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');

        $admin = new AdminEntity(
            email: "admin@email.test",
            name: $faker->firstName(),
            surname: $faker->firstNameMale(),
        );
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));
        $manager->persist($admin);


        $user = new UserEntity(
            name: $faker->firstName(),
            surname: $faker->firstNameMale(),
            patronymic: $faker->lastName(),
            email: "app@email.test",
            birthday: $faker->dateTimeBetween('-50 years', '-10 years'),
            confirmToken: new ConfirmToken('token', DateTime::current()->modify('+1 day')),
            passport: sprintf('%s%s', $faker->randomElement(['KA', 'НВ', 'НЕ']), $faker->numberBetween(100000, 999999)),
            phone: substr($faker->phoneNumber(), 0, 12),
        );

        $password = $this->passwordHasher->hashPassword($user, 'password');

        $user->setPassword($password);

        $user->setConfirmToken(null);
        $user->setStatus($user::STATUS_ACTIVE);

        $manager->persist($user);

        for ($i = 1; $i <= 10; $i++) {
            $user = new UserEntity(
                name: $faker->firstName(),
                surname: $faker->firstNameMale(),
                patronymic: $faker->lastName(),
                email: random_int(111, 999) . $faker->boolean() ? $faker->email : $faker->freeEmail,
                birthday: $faker->dateTimeBetween('-50 years', '-10 years'),
                confirmToken: new ConfirmToken('token', DateTime::current()->modify('+1 day')),
                passport: sprintf('%s%s', $faker->randomElement(['KA', 'НВ', 'НЕ']), $faker->numberBetween(100000, 999999)),
                phone: substr($faker->phoneNumber(), 0, 12),
            );

            $user->setPassword($password);

            $user->setConfirmToken(null);
            $user->setStatus($user::STATUS_ACTIVE);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
