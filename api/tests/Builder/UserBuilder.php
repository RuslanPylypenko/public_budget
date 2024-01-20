<?php

namespace App\Tests\Builder;

use App\User\ConfirmToken;
use App\User\UserEntity;
use App\Utils\DateTime;

class UserBuilder
{
    public function __construct(
        private readonly string $name = 'First',
        private readonly string $surname = 'Last',
        private readonly string $patronymic = 'Patronymic',
        private readonly string $email = 'test@app.test',
        private readonly \DateTime $birthday = new \DateTime('2010-10-10'),
        private readonly string $passport = 'НE6652322',
        private readonly int $phone = 380000000000,
        private bool $confirmed = false,
        private ?ConfirmToken $confirmToken = null,
    ) {
        $this->confirmToken = new ConfirmToken('token', DateTime::current()->modify('+ 1 day'));
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;

        return $this;
    }

    public function build(): UserEntity
    {
        $user = new UserEntity(
            $this->name,
            $this->surname,
            $this->patronymic,
            $this->email,
            $this->birthday,
            $this->confirmToken,
            $this->passport,
            $this->phone
        );

        if ($this->confirmed) {
            $user->confirmSignUp();
        }

        return $user;
    }
}