<?php

namespace App\User;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByEmail(string $email): ?UserEntity
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findByConfirmToken(string $token): ?UserEntity
    {
        return $this->findOneBy(['confirmToken.token' => $token]);
    }
}