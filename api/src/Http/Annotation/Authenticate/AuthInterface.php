<?php

namespace App\Http\Annotation\Authenticate;

interface AuthInterface
{
    public function getEmail(): string;

    public function getHashSession(): string;
}