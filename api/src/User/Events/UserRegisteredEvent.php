<?php

namespace App\User\Events;

use App\User\UserEntity;
use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
    public const NAME = 'user.registered';

    public function __construct(
        private readonly UserEntity $user,
    ) {
    }

    public function getUser(): UserEntity
    {
        return $this->user;
    }
}