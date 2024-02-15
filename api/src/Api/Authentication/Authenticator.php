<?php

declare(strict_types=1);

namespace App\Api\Authentication;

use App\Admin\AdminEntity;
use App\Http\Annotation\Authenticate\TokenManager;
use App\Http\Annotation\Authenticate\UserAuthenticatorInterface;
use App\Http\Annotation\Authenticate\AuthInterface;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Token as TokenInterface;

readonly class Authenticator implements UserAuthenticatorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function authenticate(TokenInterface $token): ?AuthInterface
    {
        $claims = $token->claims()->get(TokenManager::CLMS_LIST, []);
        $user   = $this->em->getRepository(UserEntity::class)->findOneBy([
            'email' => $token->claims()->get(TokenManager::USER_EMAIL_CLM)
        ]);
        $admin = $this->em->getRepository(AdminEntity::class)->findOneBy([
            'email' => $token->claims()->get(TokenManager::USER_EMAIL_CLM)
        ]);

        if (null === $auth = $user !== null ? $user : $admin) {
            return null;
        }

        if (
            in_array(TokenManager::HASH_SESSION_CLM, $claims, true) &&
            $auth->getHashSession() !== $token->claims()->get(TokenManager::HASH_SESSION_CLM)
        ) {
            return null;
        }

        return $auth;
    }
}