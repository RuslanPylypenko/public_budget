<?php

namespace App\User\Command\Registration\Confirm;

use App\Common\CQRS\ICommand;

readonly class Command implements ICommand
{
    public function __construct(
        public string $token
    ) {
    }
}