<?php

namespace App\Http\Annotation\Authenticate;

interface UserInterface
{
    public function getEmail(): string;

    public function getHashSession(): string;
}