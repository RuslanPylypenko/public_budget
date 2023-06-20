<?php

namespace App\Utils;

class Random
{
    public static function getRandomString(int $length = 64): string
    {
        return substr(bin2hex(random_bytes((int)($length / 2 + 1))), 0, $length);
    }
}