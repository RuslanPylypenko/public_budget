<?php

namespace App\Api\Authentication;

use App\Http\Annotation\Authenticate\TokenManager;
use App\Http\Annotation\Authenticate\UserAuthenticatorInterface;
use App\Http\Annotation\Authenticate\UserInterface;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Token as TokenInterface;

class UserAuthenticator implements UserAuthenticatorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function authenticate(TokenInterface $token): ?UserInterface
    {
        $a = $token->claims()->all();
        if (null === $user = $this->em->getRepository(UserEntity::class)->findByEmail($token->claims()->get(TokenManager::USER_EMAIL_CLM))) {
            return null;
        }

        $claims = $token->claims()->get(TokenManager::CLMS_LIST, []);
        if (
            in_array(TokenManager::HASH_SESSION_CLM, $claims, true) &&
            $user->getHashSession() !== $token->claims()->get(TokenManager::HASH_SESSION_CLM)
        ) {
            return null;
        }

        return $user;
    }
}