<?php

namespace App\Utils;

class DateTime
{
    public static function current(): \DateTime
    {
        return new \DateTime('now', new \DateTimeZone('UTC'));
    }

    public static function fromString(string $dateTime): \DateTime
    {
        return new \DateTime($dateTime, new \DateTimeZone('UTC'));
    }
}