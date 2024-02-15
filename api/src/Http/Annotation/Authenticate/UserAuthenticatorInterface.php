<?php

namespace App\Http\Annotation\Authenticate;

use Lcobucci\JWT\Token as TokenInterface;

interface UserAuthenticatorInterface
{
    public function authenticate(TokenInterface $token): ?AuthInterface;
}