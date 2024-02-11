<?php

namespace App\User\Command\SignUp\Confirm;

use App\Common\CQRS\ICommand;

readonly class Command implements ICommand
{
    public function __construct(
        public string $token
    ) {
    }
}