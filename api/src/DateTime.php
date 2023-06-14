<?php

namespace App;

class DateTime
{
    public static function current(): \DateTime
    {
        return new \DateTime('now', new \DateTimeZone('UTC'));
    }
}