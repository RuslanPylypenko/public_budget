<?php

namespace App\Utils;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

class DateTime implements ClockInterface
{
    public static function current(): \DateTime
    {
        return new \DateTime('now', new \DateTimeZone('UTC'));
    }

    public static function fromString(string $dateTime): \DateTime
    {
        return new \DateTime($dateTime, new \DateTimeZone('UTC'));
    }

    public static function fromUTC()
    {
        return new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }

    public function now(): DateTimeImmutable
    {
        // TODO: Implement now() method.
    }
}