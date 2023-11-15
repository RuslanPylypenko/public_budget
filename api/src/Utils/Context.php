<?php

namespace App\Utils;

class Context
{
    public function __construct(public array $data = [])
    {
    }

    public function add(string $nick, mixed $data): void
    {
        $this->data[$nick] = $data;
    }
}